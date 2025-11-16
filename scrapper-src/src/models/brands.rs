use regex::Regex;
use once_cell::sync::Lazy;
pub const BRANDS_DESTINATION: &str = "../../scrapper-results/brands.csv";
pub const BRANDS_SOURCE_PATH: &str = "https://sklep.sfd.pl/Producenci.aspx";

pub static REGEX_BRAND_ID: Lazy<Regex> = Lazy::new(|| {
    Regex::new(r"-p(\d+)\.html$").unwrap()
});

#[derive(Debug)]
pub struct BrandRow {
    pub id: String,
    pub name: String,
    pub url: String,
    pub img: String,
}

#[derive(Debug)]
pub struct Brand {
    pub id: Option<String>,
    pub name: String,
    pub url: String,
    pub img: Option<String>,
}

impl Brand {
    pub fn print(&self) {
        println!( "(id={}) {} => {}", self.id.as_deref().unwrap_or("-"), self.name, self.url);
    }

    pub fn flatten(&self, out: &mut Vec<BrandRow>) {
        out.push(BrandRow {
            id: self.id.clone().unwrap_or_default(),
            name: self.name.clone(),
            url: self.url.clone(),
            img: self.img.clone().unwrap_or_default(),
        });
    }
}