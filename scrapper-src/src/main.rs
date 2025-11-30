mod models;
mod utils;
mod scraper;

use anyhow::Result;

#[tokio::main]
async fn main() -> Result<()> {
    let args: Vec<String> = std::env::args().collect();
    scraper::run(args).await
}
