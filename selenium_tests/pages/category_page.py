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

    def _get_available_quantity(self):
        """Get the maximum available quantity for the current product."""
        try:
            el = self.driver.find_element(By.CSS_SELECTOR, "[data-stock]")
            data_stock = el.get_attribute('data-stock')
            print(f"  Available stock from data-stock: {data_stock}")
            if data_stock and data_stock.isdigit() and int(data_stock) > 0:
                return int(data_stock)
        except:
            pass

        # Fallback
        return 10

    def get_all_products(self):
        """Get all product elements on the page."""
        try:
            WebDriverWait(self.driver, 2).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#js-product-list"))
            )
            self.driver.execute_script("window.stop();")
        except:
            pass

        self.close_popups()

        try:
            products = self.find_elements((By.CSS_SELECTOR, "#js-product-list article"), timeout=3)
            if products:
                print(f"Found {len(products)} products")
                return products
        except:
            pass

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

            # Wait for product page
            WebDriverWait(self.driver, 3).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "button.add-to-cart, .product-add-to-cart"))
            )
            self.driver.execute_script("window.stop();")

            if self._is_out_of_stock():
                print("  Product out of stock, skipping")
                self.driver.get(category_url)
                return False

            available_qty = self._get_available_quantity()
            if quantity > available_qty:
                print(f"  Requested {quantity} but only {available_qty} available, adjusting")
                quantity = available_qty

            if quantity > 1:
                try:
                    qty_input = self.driver.find_element(By.CSS_SELECTOR, "input#quantity_wanted")
                    self.driver.execute_script("""
                        arguments[0].value = arguments[1];
                        arguments[0].dispatchEvent(new Event('change'));
                    """, qty_input, str(quantity))
                    print(f"  Quantity set to {quantity}")
                except:
                    print("  Could not set quantity, using 1")

            try:
                add_to_cart_btn = self.driver.find_element(By.CSS_SELECTOR, "button.add-to-cart")
                safe_click(self.driver, add_to_cart_btn)

                # Brief wait for cart to register the add
                time.sleep(0.3)

                errors = self.driver.find_elements(By.CSS_SELECTOR, ".alert-danger")
                for error in errors:
                    if error.is_displayed() and error.text:
                        print(f"  Error: {error.text[:50]}, skipping")
                        self.driver.get(category_url)
                        return False
            except:
                print("Could not find add to cart button")
                self.driver.get(category_url)
                return False

            # Navigate back immediately - no need to close modal
            self.driver.get(category_url)
            self.driver.execute_script("window.stop();")

            return True

        except Exception as e:
            print(f"Error adding product to cart: {e}")
            try:
                self.driver.get(category_url)
            except:
                pass
            return False

    def add_products_with_quantities(self, count=10):
        added_products = []
        added_product_names = set()
        attempts = 0
        max_attempts = count * 3

        print(f"\n  Starting to add {count} unique products...")

        while len(added_products) < count and attempts < max_attempts:
            attempts += 1
            print(f"\n  Attempt {attempts}/{max_attempts} - Added: {len(added_products)}/{count}")

            products = self.get_all_products()
            if not products:
                break

            random.shuffle(products)

            for product in products:
                product_name = self.get_product_name(product)
                if product_name in added_product_names:
                    continue

                quantity = random.randint(1, 3)
                print(f"  Adding '{product_name}' qty {quantity}")

                if self.add_product_to_cart(product, quantity=quantity, scroll=True):
                    added_products.append({'name': product_name, 'quantity': quantity})
                    added_product_names.add(product_name)
                break

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
