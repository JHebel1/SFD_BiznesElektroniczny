# How to Run the Scraper

To run the scraper, use:


`cargo run -- {command}`
or after building:
`./scraper {command}`

### `categories`
Scrapes all product categories from the store and generates:

- `scrapper-results/categories.csv`

Includes category IDs, URLs, names, depth levels, etc.  
This command should be run **first**, before scraping products.

---

### `brands`
Scrapes all brands from the store and generates:

- `scrapper-results/brands.csv`

Useful if products reference brand ID.

---

### `products`
Scrapes product data for each category listed in `categories.csv` and generates:

- `scrapper-results/products.csv`

Includes name, price, brand, category mapping, description, images, composition, and more.  
⚠️ Requires `categories.csv` to exist.

---