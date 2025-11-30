# How to run loader

## Environment Configuration
### Before running any commands, create a .env file in the project root with the following content:
```bash
PS_API_URL=https://localhost:8443/api
PS_API_KEY= "Generate api key on admin panel"
PS_LANG_ID=1
PS_ROOT_CATEGORY_ID=2
```
## You have to load categories and brands before loading products
## To add all categories:
```bash
python ./load_categories
```
## To add all brands:
```bash
python ./loadBrands
```
## To add all products:
```bash
python ./load_products
```
## To delete all categories:
```bash
python ./delete_categories
```
## To delete all products:
```bash
python ./delete_products
```
## To delete all brands:
```bash
python ./deleteBrands
```
