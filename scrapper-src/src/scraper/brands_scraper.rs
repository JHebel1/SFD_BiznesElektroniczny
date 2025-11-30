use std::collections::BTreeSet;
use std::fs::File;
use std::io::Write;
use reqwest::Client;
use scraper::{Html, Selector};
use crate::utils::constants::USER_AGENT;
use crate::utils::text::clean_text;
use crate::models::brands::{Brand, BRANDS_DESTINATION, BRANDS_SOURCE_PATH, REGEX_BRAND_ID};

pub(crate) async fn brands() -> anyhow::Result<()> {
    let url = BRANDS_SOURCE_PATH;
    let client = Client::builder().user_agent(USER_AGENT).build()?;
    let html = client.get(url).send().await?.text().await?;
    let document = Html::parse_document(&html);

    let selector = Selector::parse("li.wiki-list__item a").unwrap();

    println!("\nZnalezione marki:");

    let mut seen = BTreeSet::new();

    let mut brands_list: Vec<Brand> = Vec::new();

    for a in document.select(&selector) {
        let href = match a.value().attr("href") {
            Some(h) => h,
            None => continue,
        };

        let name = clean_text(&a);
        if name.is_empty() {
            continue;
        }

        let full_url = if href.starts_with("http") {
            href.to_string()
        } else {
            format!("https://sklep.sfd.pl{href}")
        };

        if !seen.insert(full_url.clone()) {
            continue;
        }

        let id = REGEX_BRAND_ID
            .captures(href)
            .and_then(|c| c.get(1))
            .map(|m| m.as_str().to_string());

        let brand = Brand {
            id: id.clone(),
            name: name.clone(),
            url: full_url.clone(),
            img: None,
        };

        println!("id({}) {} => {}", brand.id.clone().unwrap_or_default(), brand.name, brand.url);
        brands_list.push(brand);
    }

    save_brands_csv(&brands_list, BRANDS_DESTINATION)?;

    Ok(())
}

fn save_brands_csv(categories: &[Brand], path: &str) -> std::io::Result<()> {
    let mut rows = Vec::new();

    for cat in categories {
        cat.flatten(&mut rows);
    }

    let mut file = File::create(path)?;

    writeln!(file, "id;name;url;img")?;

    for row in rows {
        writeln!(
            file,
            "{};{};{};{}",
            row.id,
            row.name,
            row.url,
            row.img
        )?;
    }

    Ok(())
}