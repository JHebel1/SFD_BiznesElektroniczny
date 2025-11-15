mod models;
mod utils;
mod scraper;

use anyhow::Result;

#[tokio::main]
async fn main() -> Result<()> {
    scraper::run().await
}
