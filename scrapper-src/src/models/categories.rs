pub const CATEGORIES_DESTINATION: &str = "../../scrapper-results/categories.csv";
pub const CATEGORIES_SOURCE_PATH: &str = "https://sklep.sfd.pl/";
pub const REGEX_CATEGORY: Lazy<Regex> = Lazy::new(|| {
    Regex::new(r"-k\d+\.html$").unwrap()
});
pub const REGEX_CATEGORY_ID: Lazy<Regex> = Lazy::new(|| {
    Regex::new(r"-k(\d+)\.html$").unwrap()
});

#[derive(Debug)]
pub struct CategoryRow {
    pub id: String,
    pub name: String,
    pub url: String,
    pub parent_id: String,
    pub depth: usize,
}

#[derive(Debug)]
pub struct Category {
    pub id: Option<String>,
    pub name: String,
    pub url: String,
    pub childrens: Vec<Category>,
}

impl Category {
    pub fn print(&self, indent: usize) {
        let pad = " ".repeat(indent);
        println!( "{}(id={}) {} => {}", pad, self.id.as_deref().unwrap_or("-"), self.name, self.url);

        for child in &self.childrens {
            child.print(indent + 1);
        }
    }

    pub fn flatten(&self, parent_id: Option<&str>, depth: usize, out: &mut Vec<CategoryRow>) {
        out.push(CategoryRow {
            id: self.id.clone().unwrap_or_default(),
            name: self.name.clone(),
            url: self.url.clone(),
            parent_id: parent_id.unwrap_or("").to_string(),
            depth,
        });

        for child in &self.childrens {
            child.flatten(self.id.as_deref(), depth + 1, out);
        }
    }
}