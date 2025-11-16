use crate::scraper::brands_scraper::brands;
use crate::scraper::categories_scraper::categories;

pub mod categories_scraper;
pub mod brands_scraper;

pub async fn run() -> anyhow::Result<()> {
    categories().await
    brands().await
}