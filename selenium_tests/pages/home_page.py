"""Home page object."""

from selenium.webdriver.common.by import By
from pages.base_page import BasePage
from utils.helpers import wait_for_clickable


class HomePage(BasePage):
    """Home page object."""

    # Locators
    SEARCH_INPUT = (By.CSS_SELECTOR, "input[name='s'], input.search_query, input[type='search'], .search-input, #search_query_top, input[placeholder*='Search'], input[placeholder*='Szukaj']")
    SEARCH_BUTTON = (By.CSS_SELECTOR, "button[type='submit'], .search-widgets button, button.search-btn")
    DESKTOP_MENU = (By.ID, "_desktop_top_menu")
    CATEGORY_LINKS = (By.CSS_SELECTOR, "#_desktop_top_menu #top-menu > li > a")
    ALL_MENU_LINKS = (By.CSS_SELECTOR, "#_desktop_top_menu #top-menu a")
    FEATURED_PRODUCTS = (By.CSS_SELECTOR, ".featured-products .product-miniature, .featured-products .thumbnail-container")
    LOGO = (By.CSS_SELECTOR, ".logo")
    CART_LINK = (By.CSS_SELECTOR, ".blockcart, .shopping-cart")
    CART_COUNT = (By.CSS_SELECTOR, ".cart-products-count")
    USER_INFO = (By.CSS_SELECTOR, ".user-info")

    def __init__(self, driver, base_url):
        """Initialize home page."""
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        """Open home page."""
        import time
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC
        self.driver.get(self.base_url)

        # Wait only for menu to be present (not full page load)
        try:
            WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".menu, #top-menu, .header"))
            )
            time.sleep(0.5)  # Brief wait for menu to stabilize
        except:
            time.sleep(1)  # Fallback

        self.close_popups()  # Close any popups
        return self

    def search_product(self, search_term):
        """Search for a product."""
        import time
        from selenium.webdriver.common.keys import Keys

        # Try multiple search input selectors
        search_selectors = [
            (By.CSS_SELECTOR, "input[name='s']"),
            (By.CSS_SELECTOR, "input.search_query"),
            (By.CSS_SELECTOR, "input[type='search']"),
            (By.CSS_SELECTOR, ".search-input"),
            (By.CSS_SELECTOR, "#search_query_top"),
            (By.CSS_SELECTOR, "input[placeholder*='Search']"),
            (By.CSS_SELECTOR, "input[placeholder*='Szukaj']"),
            (By.CSS_SELECTOR, "#search_widget input"),
            (By.CSS_SELECTOR, ".search-widget input"),
        ]

        for selector in search_selectors:
            try:
                search_input = self.find_element(selector, timeout=3)
                self.scroll_to(search_input)
                search_input.clear()
                search_input.send_keys(search_term)
                search_input.send_keys(Keys.RETURN)
                print(f"Search performed using selector: {selector}")
                time.sleep(0.5)  # Brief wait for search to initiate
                return  # Success
            except:
                continue

        # If we get here, no selector worked
        raise Exception("Could not find search input with any selector")

    def get_categories(self):
        """Get all category links - optimized for speed."""
        try:
            # Use the selector that works for this shop first, with short timeout
            # This shop uses .menu-item a and .nav-item a selectors
            selectors_to_try = [
                (By.CSS_SELECTOR, ".menu-item a, .nav-item a"),  # Works for this shop
                (By.CSS_SELECTOR, "#_desktop_top_menu #top-menu > li > a"),
                (By.CSS_SELECTOR, "#top-menu a[data-depth='0']"),
            ]

            for selector in selectors_to_try:
                try:
                    # Very short timeout - menu should be loaded already
                    links = self.find_elements(selector, timeout=1)
                    if links and len(links) > 0:
                        print(f"Found {len(links)} links using selector: {selector}")

                        # Filter out invalid links quickly
                        valid_links = []
                        for link in links:
                            href = link.get_attribute('href')
                            if not href or 'javascript:void' in href:
                                continue
                            if href == self.base_url or href == self.base_url + '/':
                                continue
                            if '/' in href:
                                valid_links.append(link)

                        print(f"Filtered to {len(valid_links)} valid category links")

                        if len(valid_links) > 0:
                            return valid_links[:10]  # Return max 10 categories
                except:
                    continue

            print("No categories found with any selector")
            return []
        except Exception as e:
            print(f"Error in get_categories: {e}")
            return []

    def get_featured_products(self):
        """Get featured products from home page."""
        try:
            return self.find_elements(self.FEATURED_PRODUCTS, timeout=5)
        except:
            return []

    def click_category(self, index=0):
        """Click on a category by index."""
        categories = self.get_categories()
        if categories and len(categories) > index:
            self.click(categories[index])
            return True
        return False

    def go_to_cart(self):
        """Navigate to cart page."""
        cart_link = self.find_element(self.CART_LINK)
        self.click(cart_link)

    def get_cart_count(self):
        """Get number of items in cart."""
        try:
            cart_count_element = self.find_element(self.CART_COUNT, timeout=2)
            count_text = cart_count_element.text.strip()
            # Extract number from text like "(3)"
            return int(''.join(filter(str.isdigit, count_text)))
        except:
            return 0
