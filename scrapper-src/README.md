# How to Run the Scraper

To run the scraper, use:


`cargo run -- {command}`
or after building:
`./scraper {command}`

## `categories`
Scrapes all product categories from the store and generates:

- `scrapper-results/categories.csv`

Includes category IDs, URLs, names, depth levels, etc.  
This command should be run **first**, before scraping products.

### Format:
- id -> category id scraped from page
- name -> category name
- url -> category url (index view for products in category)
- parent_id -> id of category parent (if category is subcategory)
- depth -> depth as subcategory

---

## `brands`
Scrapes all brands from the store and generates:

- `scrapper-results/brands.csv`

Useful if products reference brand ID.

### Format:
- id -> brand id
- name -> brand name
- url -> brand url (index view for products in brand)
- img -> always empty

---

## `products`
Scrapes product data for each category listed in `categories.csv` and generates:

- `scrapper-results/products.csv`

Includes name, price, brand, category mapping, description, images, composition, and more.  
⚠️ Requires `categories.csv` to exist.

### Format:
- id -> product id
- name -> product name
- url -> product url (view page)
- category_id -> id of category that product belongs
- price -> price
- weight -> weight
- brand_id -> id of brand that product belongs
- price_on_unit -> "100 zł / 100 gram" || "5 zł / 1 tabletka"
- img -> product image
- description -> description
- recommended_serving -> recommended serving
- product composition -> product composition(skład)
- reviews -> always empty 

---