fn main() {
    println!("Hello, world!");
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
