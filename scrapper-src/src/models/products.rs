pub const PRODUCTS_DESTINATION: &str = "../../scrapper-results/product.csv";
pub const IMAGE_DESTINATION: &str = "../../scrapper-results/images";

#[derive(Debug)]
pub struct ProductRow {
    pub id: String,
    pub name: String,
    pub url: String,
    pub category_id: String,
    pub price: String,
    pub weight: String,
    pub brand_id: String,
    pub price_on_unit: String,
    pub img: String,
    pub second_img: String,
    pub description: String,
    pub recommended_serving: String,
    pub product_composition: String,
    pub reviews: String,
}

#[derive(Debug)]
pub struct Product {
    pub id: Option<String>,
    pub name: String,
    pub url: String,
    pub category_id: String,
    pub price: String,
    pub weight: String,
    pub brand_id: String,
    pub price_on_unit: String,
    pub img: Option<String>,
    pub second_img: Option<String>,

    pub description: String,
    pub recommended_serving: String,
    pub product_composition: String,
    pub reviews: String,
}

impl Product {
    pub fn print(&self, indent: usize) {
        let pad = " ".repeat(indent);

        println!("{}Product:", pad);
        println!("{}  id: {}", pad, self.id.as_deref().unwrap_or("-"));
        println!("{}  name: {}", pad, self.name);
        println!("{}  url: {}", pad, self.url);
        println!("{}  category_id: {}", pad, self.category_id);
        println!("{}  brand_id: {}", pad, self.brand_id);

        println!("{}  price: {}", pad, self.price);
        println!("{}  weight: {}", pad, self.weight);

        println!("{}  price_on_unit: {}", pad, self.price_on_unit);

        println!(
            "{}  img: {}",
            pad,
            self.img.as_deref().unwrap_or("-")
        );

        println!("{}  description: {}", pad, self.description);
        println!("{}  recommended_serving: {}", pad, self.recommended_serving);
        println!("{}  product_composition: {}", pad, self.product_composition);
        println!("{}  reviews: {}", pad, self.reviews);
    }

    pub fn flatten(&self, out: &mut Vec<ProductRow>) {
        out.push(ProductRow {
            id: self.id.clone().unwrap_or_default(),
            name: self.name.clone(),
            url: self.url.clone(),
            category_id: self.category_id.clone(),
            price: self.price.clone(),
            weight: self.weight.clone(),
            brand_id: self.brand_id.clone(),
            price_on_unit: self.price_on_unit.clone(),
            img: self.img.clone().unwrap_or_default(),
            second_img: self.second_img.clone().unwrap_or_default(),
            description: self.description.clone(),
            recommended_serving: self.recommended_serving.clone(),
            product_composition: self.product_composition.clone(),
            reviews: self.reviews.clone(),
        });
    }
}
