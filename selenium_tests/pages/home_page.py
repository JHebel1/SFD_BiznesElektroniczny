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
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC
        self.driver.get(self.base_url)

        try:
            WebDriverWait(self.driver, 3).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".menu, #top-menu, .header"))
            )
            self.driver.execute_script("window.stop();")
        except:
            pass

        self.close_popups()
        return self

    def search_product(self, search_term):
        """Search for a product."""
        from selenium.webdriver.common.keys import Keys

        search_input = self.find_element((By.CSS_SELECTOR, "input[name='s']"), timeout=2)
        search_input.clear()
        search_input.send_keys(search_term)
        search_input.send_keys(Keys.RETURN)

    def get_categories(self):
        """Get all category links."""
        try:
            links = self.find_elements((By.CSS_SELECTOR, ".menu-item a, .nav-item a"), timeout=1)
            valid_links = [
                link for link in links
                if (href := link.get_attribute('href'))
                and 'javascript:void' not in href
                and href not in (self.base_url, self.base_url + '/')
            ]
            print(f"Found {len(valid_links)} category links")
            return valid_links[:10]
        except:
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
