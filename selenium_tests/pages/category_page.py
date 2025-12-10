"""Category/Product listing page object."""

import time
import random
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pages.base_page import BasePage
from utils.helpers import safe_click


class CategoryPage(BasePage):
    """Category page object for product listings."""

    PRODUCT_LIST = (By.ID, "js-product-list")
    PRODUCT_NAME = (By.CSS_SELECTOR, ".product_name, .product-title, h3 a")
    PRODUCT_PRICE = (By.CSS_SELECTOR, ".price")
    CART_MODAL = (By.ID, "blockcart-modal")
    CART_MODAL_CLOSE = (By.CSS_SELECTOR, "#blockcart-modal .close")

    def __init__(self, driver):
        super().__init__(driver)

    def _is_out_of_stock(self):
        """Check if product is out of stock by checking if add button is disabled."""
        try:
            add_btn = self.driver.find_element(By.CSS_SELECTOR, "button.add-to-cart")
            return add_btn.get_attribute('disabled') is not None
        except:
            return False

    def get_all_products(self):
        """Get all product elements on the page."""
        try:
            WebDriverWait(self.driver, 2).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#js-product-list, .products, .product-list"))
            )
            try:
                self.driver.execute_script("window.stop();")
            except:
                pass
            time.sleep(0.3)
        except:
            time.sleep(0.5)

        self.close_popups()
        print(f"Current URL: {self.driver.current_url}")

        selectors = [
            (By.CSS_SELECTOR, "#js-product-list article"),
            (By.CSS_SELECTOR, ".products article"),
            (By.CSS_SELECTOR, "article.product-miniature"),
            (By.CSS_SELECTOR, ".product-miniature"),
            (By.CSS_SELECTOR, "#js-product-list .thumbnail-container"),
            (By.CSS_SELECTOR, ".thumbnail-container"),
            (By.CSS_SELECTOR, ".item_in"),
            (By.XPATH, "//article[contains(@class, 'product')]"),
        ]

        for selector in selectors:
            try:
                products = self.find_elements(selector, timeout=3)
                if products and len(products) > 0:
                    print(f"Found {len(products)} products using selector: {selector}")
                    return products
            except:
                continue

        print("No products found with any selector")
        return []

    def get_product_name(self, product_element):
        """Get product name from product element."""
        try:
            name_element = product_element.find_element(*self.PRODUCT_NAME)
            return name_element.text
        except:
            return ""

    def add_product_to_cart(self, product_element, quantity=1, scroll=True):
        """Add a product to cart by navigating to its detail page."""
        try:
            category_url = self.driver.current_url

            if scroll:
                self.scroll_to(product_element)

            product_link = product_element.find_element(*self.PRODUCT_NAME)
            safe_click(self.driver, product_link)
            time.sleep(1)

            if self._is_out_of_stock():
                print("  Product out of stock, skipping")
                self.driver.get(category_url)
                time.sleep(0.3)
                return False

            if quantity > 1:
                try:
                    qty_input = self.driver.find_element(By.CSS_SELECTOR, "input#quantity_wanted")
                    self.driver.execute_script("arguments[0].value = '';", qty_input)
                    self.driver.execute_script("arguments[0].value = arguments[1];", qty_input, str(quantity))
                    self.driver.execute_script("arguments[0].dispatchEvent(new Event('change'));", qty_input)
                    time.sleep(0.3)
                    print(f"  Quantity set to {quantity}")
                except:
                    print("  Could not set quantity, using 1")

            try:
                add_to_cart_btn = self.driver.find_element(By.CSS_SELECTOR, "button.add-to-cart")
                self.scroll_to(add_to_cart_btn)
                safe_click(self.driver, add_to_cart_btn)
                time.sleep(0.8)

                try:
                    errors = self.driver.find_elements(By.CSS_SELECTOR, ".alert-danger")
                    for error in errors:
                        if error.is_displayed() and error.text:
                            print(f"  Error: {error.text[:50]}, skipping")
                            self.driver.get(category_url)
                            time.sleep(0.3)
                            return False
                except:
                    pass
            except:
                print("Could not find add to cart button")
                self.driver.get(category_url)
                return False

            try:
                modal = self.find_element(self.CART_MODAL, timeout=1)
                if modal:
                    self.close_cart_modal()
                    time.sleep(0.2)
            except:
                time.sleep(0.3)

            self.driver.get(category_url)

            try:
                WebDriverWait(self.driver, 2).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, "#js-product-list, .products"))
                )
                try:
                    self.driver.execute_script("window.stop();")
                except:
                    pass
                time.sleep(0.2)
            except:
                time.sleep(0.4)

            return True

        except Exception as e:
            print(f"Error adding product to cart: {e}")
            try:
                if 'category_url' in locals():
                    self.driver.get(category_url)
                    time.sleep(0.5)
            except:
                pass
            return False

    def add_products_with_quantities(self, count=10):
        """Add specified number of unique products with varying quantities."""
        added_products = []
        added_product_names = set()
        attempts = 0
        max_attempts = count * 3

        print(f"\n  Starting to add {count} unique products...")

        while len(added_products) < count and attempts < max_attempts:
            attempts += 1
            print(f"\n  Attempt {attempts}/{max_attempts} - Currently added: {len(added_products)}/{count}")

            products = self.get_all_products()

            if not products:
                print("No products found on page")
                break

            available_products = list(products)
            random.shuffle(available_products)

            product_found = False
            for product in available_products:
                try:
                    product_name = self.get_product_name(product)

                    if product_name in added_product_names:
                        print(f"  Skipping '{product_name}' (already added)")
                        continue

                    quantity = random.randint(1, 3)
                    print(f"  Attempting to add '{product_name}' with quantity {quantity}")

                    if self.add_product_to_cart(product, quantity=quantity, scroll=True):
                        added_products.append({'name': product_name, 'quantity': quantity})
                        added_product_names.add(product_name)
                        print(f"  Successfully added product '{product_name}' (quantity: {quantity})")
                        print(f"  Total products added so far: {len(added_products)}")
                        product_found = True
                        time.sleep(0.3)
                    else:
                        print(f"  Failed to add product '{product_name}', will retry with fresh products")
                    break

                except Exception as e:
                    print(f"  Error processing product: {e}")
                    continue

            if not product_found:
                print("  Could not find a new product to add in this iteration")
                time.sleep(0.5)

        print(f"\n  Finished: Added {len(added_products)} products")
        return added_products

    def close_cart_modal(self):
        """Close the cart modal."""
        try:
            self.driver.execute_script("""
                var btn = document.querySelector('#blockcart-modal button.btn-secondary, #blockcart-modal button[data-dismiss="modal"]');
                if (btn) { btn.click(); return; }
                var modal = document.getElementById('blockcart-modal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show', 'in');
                }
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
            """)
            return
        except:
            pass

        selectors = [
            (By.CSS_SELECTOR, "#blockcart-modal button.btn-secondary"),
            (By.CSS_SELECTOR, "#blockcart-modal button[data-dismiss='modal']"),
            (By.CSS_SELECTOR, ".cart-content-btn button"),
        ]

        for selector in selectors:
            try:
                continue_btn = self.find_element(selector, timeout=0.5)
                safe_click(self.driver, continue_btn)
                return
            except:
                continue

        try:
            close_btn = self.find_element(self.CART_MODAL_CLOSE, timeout=0.5)
            safe_click(self.driver, close_btn)
        except:
            pass

    def click_product(self, product_element):
        """Click on product to go to product detail page."""
        product_link = product_element.find_element(*self.PRODUCT_NAME)
        self.click(product_link)

    def get_random_products(self, count=1):
        """Get random product elements."""
        products = self.get_all_products()
        if not products:
            return []
        count = min(count, len(products))
        return random.sample(products, count)
