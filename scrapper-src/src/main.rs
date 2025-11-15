mod models;

use std::collections::BTreeSet;
use std::fs::File;
use std::io::Write;
use std::pin::Pin;
use anyhow::Result;
use regex::Regex;
use reqwest::Client;
use scraper::{Html, Selector};
use crate::models::categories::{Category, CATEGORIES_DESTINATION, CATEGORIES_SOURCE_PATH};

const USER_AGENT: &str = "Mozilla/5.0 (compatible; sfd-rust-scraper/0.1)";

async fn categories() -> Result<()> {
    let url = CATEGORIES_SOURCE_PATH;
    let client = Client::builder()
        .user_agent(USER_AGENT)
        .build()?;
    let html = client.get(url).send().await?.text().await?;
    let document = Html::parse_document(&html);

    let a_selector = Selector::parse("li.primary-menu__main-list-item > a").unwrap();

    let category_href_re = Regex::new(r"-k\d+\.html$")?;
    let id_re =  Regex::new(r"-k(\d+)\.html$")?;

    println!("\nZnalezione kategorie:");

    let mut seen = BTreeSet::new();

    let mut roots: Vec<Category> = Vec::new();
    for a in document.select(&a_selector) {
        let href = match a.value().attr("href") {
            Some(h) => h,
            None => continue,
        };

        if !category_href_re.is_match(href) {
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

        let id = id_re
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
            &category_href_re,
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
    ) -> Pin<Box<dyn Future<Output = Result<Category>> + 'a>>
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
                childrens.push(Category {
                    id,
                    name: child_name,
                    url: full_url,
                    childrens: Vec::new(),
                });
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
}
