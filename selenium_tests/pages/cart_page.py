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
        import time
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC
        cart_url = f"{self.base_url}?controller=cart"
        self.driver.get(cart_url)

        # Wait only for cart container (not full page load)
        try:
            WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".cart-grid, .cart-container, #main"))
            )
            time.sleep(0.3)  # Brief stabilization
        except:
            time.sleep(0.5)  # Fallback

        self.close_popups()  # Close any popups (newsletter, etc.)
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
        try:
            remove_btn = item_element.find_element(*self.REMOVE_BUTTON)
            self.scroll_to(remove_btn)
            safe_click(self.driver, remove_btn)
            time.sleep(1)  # Wait for cart to update
            return True
        except Exception as e:
            print(f"Error removing product: {e}")
            return False

    def remove_products(self, count=3):
        """
        Remove specified number of products from cart.
        Returns list of removed product names.
        """
        removed_products = []

        for _ in range(count):
            items = self.get_cart_items()
            if not items:
                break

            # Get first item
            item = items[0]

            try:
                # Get product name before removing
                name_element = item.find_element(*self.PRODUCT_NAME)
                product_name = name_element.text

                # Remove the product
                if self.remove_product(item):
                    removed_products.append(product_name)
                    time.sleep(0.5)
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
        """Click proceed to checkout button or navigate directly to checkout."""
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC

        # Try to click the checkout button
        checkout_selectors = [
            (By.CSS_SELECTOR, ".checkout a.btn-primary"),
            (By.CSS_SELECTOR, ".checkout a"),
            (By.CSS_SELECTOR, "a.btn-primary[href*='order']"),
            (By.CSS_SELECTOR, "a[href*='controller=order']"),
            (By.XPATH, "//a[contains(@href, 'order') and contains(@class, 'btn')]"),
        ]

        for selector in checkout_selectors:
            try:
                checkout_btn = self.find_element(selector, timeout=2)
                self.scroll_to(checkout_btn)
                self.click(checkout_btn)
                print(f"Clicked checkout button: {selector}")

                # Wait for checkout page to load
                try:
                    WebDriverWait(self.driver, 5).until(
                        EC.presence_of_element_located((By.CSS_SELECTOR, "#checkout, .checkout-step, [id*='checkout']"))
                    )
                    print("Checkout page loaded")
                    return
                except:
                    # Check if URL contains 'order'
                    if 'order' in self.driver.current_url:
                        print("On checkout page (URL check)")
                        return
            except:
                continue

        # Fallback: navigate directly to checkout URL
        print("Could not find checkout button, navigating directly to checkout")
        checkout_url = f"{self.base_url}?controller=order"
        self.driver.get(checkout_url)
        time.sleep(1)

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
