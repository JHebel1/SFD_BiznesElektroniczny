"""Shopping cart page object."""

import time
from selenium.webdriver.common.by import By
from pages.base_page import BasePage
from utils.helpers import wait_for_element, safe_click


class CartPage(BasePage):
    """Shopping cart page object."""

    # Locators
    CART_ITEMS = (By.CSS_SELECTOR, ".cart-item")
    PRODUCT_NAME = (By.CSS_SELECTOR, ".product_name")
    PRODUCT_PRICE = (By.CSS_SELECTOR, ".product-price")
    QUANTITY_INPUT = (By.CSS_SELECTOR, ".js-cart-line-product-quantity")
    REMOVE_BUTTON = (By.CSS_SELECTOR, ".remove-from-cart")
    CART_TOTAL = (By.CSS_SELECTOR, ".cart-total .value")
    PROCEED_TO_CHECKOUT_BUTTON = (By.CSS_SELECTOR, ".checkout a, a[href*='order']")
    EMPTY_CART_MESSAGE = (By.CSS_SELECTOR, ".cart-grid-body .alert")
    CART_SUMMARY = (By.CSS_SELECTOR, ".cart-summary")

    def __init__(self, driver, base_url):
        """Initialize cart page."""
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        """Open cart page."""
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC
        self.driver.get(f"{self.base_url}?controller=cart")

        try:
            WebDriverWait(self.driver, 3).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".cart-grid, .cart-container, #main"))
            )
            self.driver.execute_script("window.stop();")
        except:
            pass

        self.close_popups()
        return self

    def get_cart_items(self):
        """Get all cart item elements."""
        try:
            return self.find_elements(self.CART_ITEMS, timeout=5)
        except:
            return []

    def get_cart_items_count(self):
        """Get number of items in cart."""
        items = self.get_cart_items()
        return len(items)

    def get_product_names(self):
        """Get names of all products in cart."""
        items = self.get_cart_items()
        names = []
        for item in items:
            try:
                name_element = item.find_element(*self.PRODUCT_NAME)
                names.append(name_element.text)
            except:
                continue
        return names

    def remove_product(self, item_element):
        """Remove a product from cart."""
        from selenium.webdriver.support.ui import WebDriverWait
        try:
            items_before = len(self.find_elements(self.CART_ITEMS, timeout=3))

            remove_btn = item_element.find_element(*self.REMOVE_BUTTON)
            self.scroll_to(remove_btn)
            safe_click(self.driver, remove_btn)

            WebDriverWait(self.driver, 5).until(
                lambda d: len(d.find_elements(*self.CART_ITEMS)) < items_before
            )
            return True
        except Exception as e:
            print(f"Error removing product: {e}")
            return False

    def remove_products(self, count=3):
        """Remove specified number of products from cart."""
        removed_products = []

        for _ in range(count):
            items = self.get_cart_items()
            if not items:
                break

            item = items[0]
            try:
                name_element = item.find_element(*self.PRODUCT_NAME)
                product_name = name_element.text

                if self.remove_product(item):
                    removed_products.append(product_name)
            except Exception as e:
                print(f"Error in remove_products: {e}")
                continue

        return removed_products

    def is_cart_empty(self):
        """Check if cart is empty."""
        return self.is_displayed(self.EMPTY_CART_MESSAGE, timeout=3)

    def get_cart_total(self):
        """Get cart total amount."""
        try:
            total_element = self.find_element(self.CART_TOTAL)
            return total_element.text
        except:
            return "0"

    def proceed_to_checkout(self):
        """Click proceed to checkout button."""
        try:
            checkout_btn = self.find_element((By.CSS_SELECTOR, ".checkout a.btn-primary"), timeout=2)
            self.scroll_to(checkout_btn)
            self.click(checkout_btn)
        except:
            self.driver.get(f"{self.base_url}?controller=order")

    def update_quantity(self, item_element, quantity):
        """Update product quantity."""
        try:
            qty_input = item_element.find_element(*self.QUANTITY_INPUT)
            qty_input.clear()
            qty_input.send_keys(str(quantity))
            time.sleep(1)  # Wait for cart to update
            return True
        except:
            return False
