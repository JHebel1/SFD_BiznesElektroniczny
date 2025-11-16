use crate::scraper::brands_scraper::brands;
use crate::scraper::categories_scraper::categories;
use crate::scraper::products_scraper::products;

pub mod categories_scraper;
pub mod brands_scraper;
pub mod products_scraper;

pub async fn run(args:Vec<String>) -> anyhow::Result<()> {
    match args[0].as_str() {
        "categories" => categories().await?,
        "brands" => brands().await?,
        "products" => products().await?,
        _ => println!("Nieznana komenda")
    }
    Ok(())
}