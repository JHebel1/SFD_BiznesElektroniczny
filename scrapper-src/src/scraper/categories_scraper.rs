use std::collections::BTreeSet;
use std::fs::File;
use std::io::{BufRead, BufReader, Write};
use std::pin::Pin;
use regex::Regex;
use anyhow::Result;
use reqwest::Client;
use scraper::{Html, Selector};
use crate::models::categories::{Category, CategoryRow, CATEGORIES_DESTINATION, CATEGORIES_SOURCE_PATH, REGEX_CATEGORY, REGEX_CATEGORY_ID};
use crate::utils::constants::USER_AGENT;
use crate::utils::text::{clean_text, escape_csv_field};

pub(crate) async fn categories() -> Result<()> {
    let url = CATEGORIES_SOURCE_PATH;
    let client = Client::builder().user_agent(USER_AGENT).build()?;
    let html = client.get(url).send().await?.text().await?;
    let document = Html::parse_document(&html);

    let a_selector = Selector::parse("li.primary-menu__main-list-item > a").unwrap();

    println!("\nZnalezione kategorie:");

    let mut seen = BTreeSet::new();

    let mut roots: Vec<Category> = Vec::new();
    for a in document.select(&a_selector) {
        let href = match a.value().attr("href") {
            Some(h) => h,
            None => continue,
        };

        if !REGEX_CATEGORY.is_match(href) {
            continue;
        }

        let text = clean_text(&a);

        if text.is_empty() {
            continue;
        }

        let full_url = if href.starts_with("http") {
            href.to_string()
        } else {
            format!("{}{}", CATEGORIES_SOURCE_PATH , href)
        };

        let id = REGEX_CATEGORY_ID
            .captures(href)
            .and_then(|c| c.get(1))
            .map(|m| m.as_str().to_string());

        if !seen.insert(full_url.clone()) {
            continue;
        }

        let root = crawl_category(
            id,
            text.clone(),
            full_url.clone(),
            &client,
            &REGEX_CATEGORY,
            3,
            0,
            &mut seen,
        ).await.await?;

        root.print(0);
        roots.push(root);
    }
    save_categories_csv(&roots, CATEGORIES_DESTINATION)?;

    Ok(())
}

async fn crawl_category<'a>(
    id: Option<String>,
    name: String,
    url: String,
    client: &'a Client,
    category_href_re: &'a Regex,
    max_depth: usize,
    depth: usize,
    seen_urls: &'a mut BTreeSet<String>,
) -> Pin<Box<dyn Future<Output =anyhow::Result<Category>> + 'a>>
{
    Box::pin(async move {
        if depth >= max_depth {
            return Ok(Category {
                id,
                name,
                url,
                childrens: Vec::new(),
            });
        }

        let html = client.get(&url).send().await?.text().await?;
        let document = Html::parse_document(&html);
        let a_selector = Selector::parse("a").unwrap();

        let id_re =  Regex::new(r"-k(\d+)\.html$")?;
        let mut childrens = Vec::new();
        let mut local_seen = BTreeSet::new();

        for a in document.select(&a_selector) {
            let href = match a.value().attr("href") {
                Some(h) => h,
                None => continue,
            };

            if !category_href_re.is_match(href) {
                continue;
            }

            let full_url = if href.starts_with("http") {
                href.to_string()
            } else {
                format!("{}{}", CATEGORIES_SOURCE_PATH , href)
            };

            if full_url == url {
                continue;
            }

            if !local_seen.insert(full_url.clone()) {
                continue;
            }

            let child_name = clean_text(&a);
            if child_name.is_empty() {
                continue;
            }

            let id = id_re
                .captures(href)
                .and_then(|c| c.get(1))
                .map(|m| m.as_str().to_string());

            if !seen_urls.insert(full_url.clone()) {
                continue;
            }

            let child = crawl_category(
                id,
                child_name,
                full_url,
                client,
                category_href_re,
                max_depth,
                depth + 1,
                seen_urls,
            ).await.await?;

            childrens.push(child);
        }

        Ok(Category { id, name, url, childrens })
    })
}

pub fn load_categories(
    path: &str,
    target_depth: usize,
) -> Result<Vec<CategoryRow>> {
    let file = File::open(path)?;
    let reader = BufReader::new(file);

    let mut result = Vec::new();

    for (i, line) in reader.lines().enumerate() {
        let line = line?;
        if i == 0 {
            continue;
        }

        let line = line.trim();
        if line.is_empty() {
            continue;
        }

        let parts: Vec<&str> = line.split(';').collect();
        if parts.len() < 5 {
            continue;
        }

        let depth: usize = parts[4].parse().unwrap_or(0);
        if depth != target_depth {
            continue;
        }

        result.push(CategoryRow {
            id: parts[0].trim().to_string(),
            name: parts[1].trim().to_string(),
            url: parts[2].trim().to_string(),
            parent_id: parts[3].trim().to_string(),
            depth,
        });
    }

    Ok(result)
}

fn save_categories_csv(categories: &[Category], path: &str) -> std::io::Result<()> {
    let mut rows = Vec::new();

    for cat in categories {
        cat.flatten(None, 0, &mut rows);
    }

    let mut file = File::create(path)?;

    writeln!(file, "id;name;url;parent_id;depth")?;

    for row in rows {
        writeln!(
            file,
            "{};{};{};{};{}",
            row.id,
            escape_csv_field(&row.name),
            row.url,
            row.parent_id,
            row.depth
        )?;
    }

    Ok(())
}