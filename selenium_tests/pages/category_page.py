"""Category/Product listing page object."""

import time
import random
from selenium.webdriver.common.by import By
from pages.base_page import BasePage
from utils.helpers import wait_for_element, scroll_to_element, safe_click


class CategoryPage(BasePage):
    """Category page object for product listings."""

    # Locators
    PRODUCT_LIST = (By.ID, "js-product-list")
    PRODUCT_ITEMS = (By.CSS_SELECTOR, "article.product-miniature, .thumbnail-container, .item-product")
    PRODUCT_MINIATURE_ALT = (By.CSS_SELECTOR, ".product-miniature")
    THUMBNAIL_CONTAINER = (By.CSS_SELECTOR, ".thumbnail-container")
    PRODUCT_NAME = (By.CSS_SELECTOR, ".product_name, .product-title, h3 a")
    PRODUCT_PRICE = (By.CSS_SELECTOR, ".price")
    ADD_TO_CART_BUTTON = (By.CSS_SELECTOR, "button.add-to-cart, .ajax_add_to_cart_button, button[data-button-action='add-to-cart']")
    QUICK_VIEW = (By.CSS_SELECTOR, ".quick_view")
    CART_MODAL = (By.ID, "blockcart-modal")
    CART_MODAL_CLOSE = (By.CSS_SELECTOR, "#blockcart-modal .close")
    CONTINUE_SHOPPING = (By.CSS_SELECTOR, ".btn-primary[data-dismiss='modal']")
    PROCEED_TO_CHECKOUT = (By.CSS_SELECTOR, "#blockcart-modal .cart-content-btn a")
    PRODUCT_ADDED_CONFIRMATION = (By.CSS_SELECTOR, ".cart-content")
    IN_STOCK_PRODUCTS = (By.CSS_SELECTOR, ".product-miniature:has(.in-stock)")

    def __init__(self, driver):
        """Initialize category page."""
        super().__init__(driver)

    def _get_available_stock(self):
        """Get available stock from product page. Returns None if can't determine."""
        try:
            # Try to find stock quantity on product page
            stock_selectors = [
                (By.CSS_SELECTOR, "#product-availability"),
                (By.CSS_SELECTOR, ".product-quantities span"),
                (By.CSS_SELECTOR, "[data-stock]"),
                (By.CSS_SELECTOR, ".product-availability"),
            ]

            for selector in stock_selectors:
                try:
                    stock_elem = self.find_element(selector, timeout=1)
                    stock_text = stock_elem.text.strip()

                    # Try to extract number from text like "100 Items" or "Dostępnych produktów: 50"
                    import re
                    numbers = re.findall(r'\d+', stock_text)
                    if numbers:
                        return int(numbers[0])

                    # Check for data attribute
                    data_stock = stock_elem.get_attribute('data-stock')
                    if data_stock:
                        return int(data_stock)
                except:
                    continue

            # If we can't find stock info, assume it's available (return high number)
            return 99
        except:
            return 99  # Default to allowing purchase

    def get_all_products(self):
        """Get all product elements on the page."""
        # Don't wait for full page load - just wait for product container to appear
        # This is much faster than waiting for all images to load
        try:
            # Wait for the product list container to be present
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.webdriver.support import expected_conditions as EC
            WebDriverWait(self.driver, 2).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#js-product-list, .products, .product-list"))
            )
            # Stop page load once we have the container - don't wait for images
            try:
                self.driver.execute_script("window.stop();")
            except:
                pass
            time.sleep(0.3)  # Short wait for DOM stability
        except:
            time.sleep(0.5)  # Short fallback

        self.close_popups()  # Close any popups that might block interaction

        print(f"Current URL: {self.driver.current_url}")

        # Try multiple product selectors (ordered by most common first)
        # First selector is what works for this specific shop
        selectors = [
            (By.CSS_SELECTOR, "#js-product-list article"),  # Works for this shop
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
                    print(f"✓ Found {len(products)} products using selector: {selector}")
                    return products
            except Exception as e:
                # Only print detailed errors if no selector works
                continue

        # If we get here, no selector worked
        print("⚠ No products found with any selector")
        print(f"Page title: {self.driver.title}")
        print("Attempted selectors:")
        for selector in selectors:
            print(f"  - {selector}")
        return []

    def get_product_name(self, product_element):
        """Get product name from product element."""
        try:
            name_element = product_element.find_element(*self.PRODUCT_NAME)
            return name_element.text
        except:
            return ""

    def add_product_to_cart(self, product_element, quantity=1, scroll=True):
        """
        Add a product to cart by navigating to its detail page.

        Args:
            product_element: The product element from the listing page
            quantity: Number of units to add (default: 1)
            scroll: Whether to scroll to element before clicking

        This method:
        1. Saves the current category page URL
        2. Clicks on the product to go to its detail page
        3. Sets the quantity if > 1
        4. Adds the product to cart from the detail page
        5. Navigates back to the category page
        """
        try:
            # Save current URL to return to after adding product
            category_url = self.driver.current_url

            if scroll:
                self.scroll_to(product_element)

            # Click on product link to go to detail page
            product_link = product_element.find_element(*self.PRODUCT_NAME)
            safe_click(self.driver, product_link)

            # Wait only for the add to cart button to appear (not full page load)
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.webdriver.support import expected_conditions as EC
            try:
                WebDriverWait(self.driver, 3).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, "button.add-to-cart, .add-to-cart, button[data-button-action='add-to-cart']"))
                )
                # Stop page load once we have the button - don't wait for images
                try:
                    self.driver.execute_script("window.stop();")
                except:
                    pass
            except:
                time.sleep(0.5)  # Fallback - shorter

            # Check available stock and limit quantity
            available_stock = self._get_available_stock()
            if available_stock is not None and available_stock > 0:
                if quantity > available_stock:
                    print(f"  ⚠ Requested {quantity} but only {available_stock} available, limiting")
                    quantity = available_stock
            elif available_stock == 0:
                print(f"  ⚠ Product out of stock, skipping")
                self.driver.get(category_url)
                return False

            # Set quantity
            quantity_selectors = [
                (By.CSS_SELECTOR, "input#quantity_wanted"),
                (By.CSS_SELECTOR, "input[name='qty']"),
                (By.CSS_SELECTOR, ".product-quantity input"),
                (By.CSS_SELECTOR, "input.qty"),
            ]

            for selector in quantity_selectors:
                try:
                    qty_input = self.find_element(selector, timeout=2)
                    qty_input.clear()
                    time.sleep(0.1)
                    self.driver.execute_script("arguments[0].value = '';", qty_input)
                    qty_input.send_keys(str(quantity))

                    actual_value = qty_input.get_attribute('value')
                    if actual_value == str(quantity):
                        print(f"  ✓ Quantity set to {quantity}")
                        time.sleep(0.2)
                        break
                except Exception as e:
                    continue

            # Try to find and click add to cart button on product detail page
            add_to_cart_selectors = [
                (By.CSS_SELECTOR, "button.add-to-cart"),
                (By.CSS_SELECTOR, ".add-to-cart"),
                (By.CSS_SELECTOR, "button[data-button-action='add-to-cart']"),
                (By.CSS_SELECTOR, ".btn-primary.add-to-cart"),
                (By.CSS_SELECTOR, "#add-to-cart-or-refresh button"),
                (By.XPATH, "//button[contains(@class, 'add-to-cart')]"),
            ]

            added = False
            for selector in add_to_cart_selectors:
                try:
                    add_to_cart_btn = self.find_element(selector, timeout=2)
                    self.scroll_to(add_to_cart_btn)
                    safe_click(self.driver, add_to_cart_btn)
                    time.sleep(0.5)  # Short wait for AJAX to start
                    added = True
                    break
                except:
                    continue

            if not added:
                print("⚠ Could not find add to cart button on product page")
                self.driver.get(category_url)  # Go back to category
                return False

            # Check if modal appears and close it immediately
            try:
                # Use very short timeout - modal should appear almost instantly
                modal = self.find_element(self.CART_MODAL, timeout=1)
                if modal:
                    self.close_cart_modal()
                    time.sleep(0.2)  # Minimal wait after close
            except:
                # No modal, cart was updated silently - this is fine
                time.sleep(0.3)

            # Navigate back to category page and stop loading early
            self.driver.get(category_url)

            # Stop page load early once we have the product container
            # This prevents waiting for all product images to load
            from selenium.webdriver.support.ui import WebDriverWait
            from selenium.webdriver.support import expected_conditions as EC
            try:
                # Wait for product container
                WebDriverWait(self.driver, 2).until(
                    EC.presence_of_element_located((By.CSS_SELECTOR, "#js-product-list, .products"))
                )
                # Stop page load immediately - we don't need images
                try:
                    self.driver.execute_script("window.stop();")
                except:
                    pass
                time.sleep(0.2)  # Minimal stabilization
            except:
                time.sleep(0.4)  # Short fallback

            return True

        except Exception as e:
            print(f"Error adding product to cart: {e}")
            # Try to go back to category page
            try:
                if 'category_url' in locals():
                    self.driver.get(category_url)
                    time.sleep(0.5)
            except:
                pass
            return False

    def add_products_with_quantities(self, count=10):
        """
        Add specified number of unique products with varying quantities.

        Args:
            count: Number of unique products to add (each will be added 1-3 times)

        Returns:
            List of added product dictionaries with name and quantity
        """
        added_products = []
        added_product_names = set()  # Track which products we've already added
        attempts = 0
        max_attempts = count * 3  # Prevent infinite loop

        print(f"\n  Starting to add {count} unique products...")

        while len(added_products) < count and attempts < max_attempts:
            attempts += 1
            print(f"\n  Attempt {attempts}/{max_attempts} - Currently added: {len(added_products)}/{count}")

            # Re-fetch products each time to avoid stale element references
            products = self.get_all_products()

            if not products:
                print("⚠ No products found on page")
                break

            # Shuffle to get random selection
            available_products = list(products)
            random.shuffle(available_products)

            # Find a product we haven't added yet
            product_found = False
            for product in available_products:
                try:
                    product_name = self.get_product_name(product)

                    # Skip if we've already added this product
                    if product_name in added_product_names:
                        print(f"  ⊘ Skipping '{product_name}' (already added)")
                        continue

                    # Add product with varying quantity (1-3 units)
                    quantity = random.randint(1, 3)
                    print(f"  → Attempting to add '{product_name}' with quantity {quantity}")

                    # Add product once with the specified quantity
                    if self.add_product_to_cart(product, quantity=quantity, scroll=True):
                        added_products.append({
                            'name': product_name,
                            'quantity': quantity
                        })
                        added_product_names.add(product_name)
                        print(f"  ✓ Successfully added product '{product_name}' (quantity: {quantity})")
                        print(f"  Total products added so far: {len(added_products)}")
                        product_found = True

                        # Small delay to ensure state is updated
                        time.sleep(0.3)

                        # Break to re-fetch products for next iteration
                        break
                    else:
                        print(f"  ✗ Failed to add product '{product_name}'")

                except Exception as e:
                    print(f"  ✗ Error processing product: {e}")
                    continue

            if not product_found:
                print("  ⚠ Could not find a new product to add in this iteration")
                time.sleep(0.5)  # Wait before retrying

        print(f"\n  Finished: Added {len(added_products)} products")
        return added_products

    def close_cart_modal(self):
        """Close the cart modal if it appears - optimized for speed."""
        # First try JavaScript to close modal instantly (fastest method)
        try:
            self.driver.execute_script("""
                // Try to click continue shopping button
                var btn = document.querySelector('#blockcart-modal button.btn-secondary, #blockcart-modal button[data-dismiss="modal"]');
                if (btn) { btn.click(); return; }

                // Or just hide the modal
                var modal = document.getElementById('blockcart-modal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show', 'in');
                }
                // Remove backdrop
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
            """)
            return
        except:
            pass

        # Fallback: try clicking buttons with very short timeouts
        continue_shopping_selectors = [
            (By.CSS_SELECTOR, "#blockcart-modal button.btn-secondary"),
            (By.CSS_SELECTOR, "#blockcart-modal button[data-dismiss='modal']"),
            (By.CSS_SELECTOR, ".cart-content-btn button"),
        ]

        for selector in continue_shopping_selectors:
            try:
                continue_btn = self.find_element(selector, timeout=0.5)
                safe_click(self.driver, continue_btn)
                return  # Successfully closed
            except:
                continue

        # Last resort: close button
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

        # Get min of requested count and available products
        count = min(count, len(products))
        return random.sample(products, count)
