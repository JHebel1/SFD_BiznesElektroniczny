use std::collections::HashSet;
use anyhow::Result;
use reqwest::Client;
use scraper::{Html, Selector};

use crate::models::categories::{CategoryRow, CATEGORIES_DESTINATION};
use crate::models::products::{Product, IMAGE_DESTINATION, PRODUCTS_DESTINATION};
use crate::utils::text::{clean_text, clean_text_html, escape_csv_field};
use std::fs::File;
use tokio::fs as tokio_fs;
use std::io::Write;
use std::path::{Path, PathBuf};
use regex::Regex;
use crate::models::brands::REGEX_BRAND_ID;
use crate::scraper::categories_scraper::load_categories;

const MAX_NUMBER_OF_PRODUCTS: usize = 12;
const TARGET_DEPTH: usize = 3;

use crate::utils::constants::USER_AGENT;pub async fn products() -> Result<()> {
    let categories: Vec<CategoryRow> = load_categories(CATEGORIES_DESTINATION, TARGET_DEPTH)?;

    let client = Client::builder()
        .user_agent(USER_AGENT)
        .build()?;

    let mut products: Vec<Product> = Vec::new();

    for cat in &categories {
        println!("Kategorie: {} ({})", cat.name, cat.url);

        let product_links = collect_product_links_for_category(&client, &cat.url, MAX_NUMBER_OF_PRODUCTS).await?;

        for product_url in product_links {
            let product = scrape_product_page(&client, &product_url, cat).await?;
            //product.print(0);
            products.push(product);
        }
    }

    products.retain(|p| p.id.is_some());

    let mut seen = HashSet::new();
    products.retain(|p| seen.insert(p.id.clone().unwrap()));

    save_products_csv(&products, PRODUCTS_DESTINATION)?;

    Ok(())
}

async fn collect_product_links_for_category(
    client: &Client,
    category_url: &str,
    max_products: usize,
) -> Result<Vec<String>> {
    let html = client.get(category_url).send().await?.text().await?;
    let document = Html::parse_document(&html);

    let product_tile_selector = Selector::parse("article.products-list__box").unwrap();
    let link_selector = Selector::parse("a").unwrap();

    let mut links = Vec::new();

    println!("{}", links.len());
    for tile in document.select(&product_tile_selector) {
        if links.len() >= max_products {
            break;
        }

        let link_el = match tile.select(&link_selector).next() {
            Some(el) => el,
            None => continue,
        };

        let href = match link_el.value().attr("href") {
            Some(h) => h,
            None => continue,
        };

        let product_url = if href.starts_with("http") {
            href.to_string()
        } else {
            format!("https://sklep.sfd.pl{href}")
        };

        links.push(product_url);
    }

    Ok(links)
}

async fn scrape_product_page(
    client: &Client,
    product_url: &str,
    cat: &CategoryRow,
) -> Result<Product> {
    let html = client.get(product_url).send().await?.text().await?;
    let document = Html::parse_document(&html);

    let id_re = Regex::new(r"-opis(\d+)\.html$")?;
    let id = id_re
        .captures(product_url)
        .and_then(|c| c.get(1))
        .map(|m| m.as_str().to_string());


    let brand_selector = Selector::parse("a.product-name__brand").unwrap();
    let brand_id = document
        .select(&brand_selector)
        .next()
        .and_then(|el| el.value().attr("href"))
        .and_then(|href| {
            REGEX_BRAND_ID
                .captures(href)
                .and_then(|c| c.get(1))
                .map(|m| m.as_str().to_string())
        })
        .unwrap_or_default();

    let name_selector = Selector::parse("span.product-name__name").unwrap();
    let name = document
        .select(&name_selector)
        .next()
        .map(|el| clean_text(&el))
        .unwrap_or_default();

    let price_selector = Selector::parse("span.product-aside__price").unwrap();
    let price = document
        .select(&price_selector)
        .next()
        .map(|el| clean_text(&el))
        .unwrap_or_default();

    let weight_selector = Selector::parse("span.product-name__package").unwrap();
    let weight = document
        .select(&weight_selector)
        .next()
        .map(|el| clean_text(&el))
        .unwrap_or_default();

    let price_on_unit_selector = Selector::parse("span.product-unit__price").unwrap();
    let price_on_unit = document
        .select(&price_on_unit_selector)
        .next()
        .map(|el| clean_text(&el))
        .unwrap_or_default();

    let description_selector = Selector::parse("#opis").unwrap();
    let description = document
        .select(&description_selector)
        .next()
        .map(|el| {
            let html = el.inner_html();
            clean_text_html(&html)
        })
        .unwrap_or_default();

    let recommended_serving_selector = Selector::parse("#dawkowanie").unwrap();
    let recommended_serving = document
        .select(&recommended_serving_selector)
        .next()
        .map(|el| {
            let html = el.inner_html();
            clean_text_html(&html)
        })
        .unwrap_or_default();

    let product_composition_selector = Selector::parse("#wartosci").unwrap();
    let product_composition = document
        .select(&product_composition_selector)
        .next()
        .map(|el| {
            let html = el.inner_html();
            clean_text_html(&html)
        })
        .unwrap_or_default();

    let gallery_selector = Selector::parse("div.product-gallery a.product-gallery__img").unwrap();

    let img_url = document
        .select(&gallery_selector)
        .next()
        .and_then(|a| a.value().attr("href"))
        .map(|s| s.to_string());

    let mut local_img_path = None;

    if let Some(url) = &img_url {
        match download_image(url, IMAGE_DESTINATION).await {
            Ok(path) => local_img_path = Some(path),
            Err(e) => eprintln!("Error downloading image {}: {}", url, e),
        }
    }

    let reviews = String::new();

    Ok(Product {
        id,
        name,
        url: product_url.to_string(),
        category_id: cat.id.clone(),
        price,
        weight,
        brand_id,
        price_on_unit: price_on_unit.clone(),
        img: local_img_path,
        description: escape_csv_field(&*description),
        recommended_serving: escape_csv_field(&*recommended_serving),
        product_composition: escape_csv_field(&*product_composition),
        reviews,
    })
}

fn save_products_csv(products: &[Product], path: &str) -> std::io::Result<()> {
    let mut rows = Vec::new();
    for p in products {
        p.flatten(&mut rows);
    }

    let mut file = File::create(path)?;

    writeln!(
        file,
        "id;name;url;category_id;price;weight;brand_id;price_on_unit;img;description;recommended_serving;product_composition;reviews"
    )?;

    for row in rows {
        writeln!(
            file,
            "{};{};{};{};{};{};{};{};{};{};{};{};{}",
            row.id,
            row.name,
            row.url,
            row.category_id,
            row.price,
            row.weight,
            row.brand_id,
            row.price_on_unit,
            row.img,
            row.description,
            row.recommended_serving,
            row.product_composition,
            row.reviews,
        )?;
    }

    Ok(())
}

pub async fn download_image(url: &str, dest_dir: &str) -> anyhow::Result<String> {
    let client = Client::new();

    let resp = client.get(url).send().await?.error_for_status()?;
    let bytes = resp.bytes().await?;

    let filename = url.split('/').last().unwrap_or("image.jpg");

    let path: PathBuf = Path::new(dest_dir).join(filename);

    if let Some(parent) = path.parent() {
        tokio_fs::create_dir_all(parent).await?;
    }

    tokio_fs::write(&path, &bytes).await?;

    Ok(path.to_string_lossy().to_string())
}