# Creators

Adam Klamrowski (198199), Jakub Hebel (197719), Konrad Cichosz (197648), Marcel Kańduła (197677)

# Description

This project aims to copy content of: https://sklep.sfd.pl/ using Prestashop and other tools.

# Prestashop version

1.7.8.11

# Running the project

To run the project, you need to be in the `shop-src` folder.

## Starting the container

```bash
make up
```

## Restoring the database

```bash
make restore
```

## Stopping the container

```bash
make down
```

## Creating a database dump

```bash
make dump
```

## Checking if it works

- Prestashop: [http://localhost:8443](http://localhost:8443)

## To run scraper, use:

`cargo run --package rust-scrapper --bin rust-scrapper {command}`

## `categories`

Scrapes all product categories from the store and generates:

- `scrapper-results/categories.csv`

## `brands`

Scrapes all brands from the store and generates:

- `scrapper-results/brands.csv`

## `products`

Scrapes product data for each category listed in `categories.csv` and generates:

- `scrapper-results/products.csv`

Includes name, price, brand, category mapping, description, images, composition, and more.  
⚠️ Requires `categories.csv` to exist.
