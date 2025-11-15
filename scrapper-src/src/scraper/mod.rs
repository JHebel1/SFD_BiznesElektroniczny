use crate::scraper::categories_scraper::categories;

pub mod categories_scraper;

pub async fn run() -> anyhow::Result<()> {
    categories().await
}