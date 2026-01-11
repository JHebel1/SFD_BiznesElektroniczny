from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pages.base_page import BasePage
from utils.helpers import safe_click


class CartPage(BasePage):

    CART_ITEMS = (By.CSS_SELECTOR, ".cart-item")
    PRODUCT_NAME = (By.CSS_SELECTOR, ".product-line-info a")
    REMOVE_BUTTON = (By.CSS_SELECTOR, ".remove-from-cart")
    PROCEED_TO_CHECKOUT = (By.CSS_SELECTOR, ".checkout a.btn-primary")

    def __init__(self, driver, base_url):
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        self.driver.get(f"{self.base_url}?controller=cart")
        try:
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, ".cart-grid, .cart-container, #main"))
            )
            self.driver.execute_script("window.stop();")
        except:
            pass
        self.close_popups()
        return self

    def get_cart_items(self):
        try:
            return self.find_elements(self.CART_ITEMS, timeout=5)
        except:
            return []

    def get_cart_items_count(self):
        return len(self.get_cart_items())

    def remove_product(self, item_element):
        try:
            items_before = len(self.find_elements(self.CART_ITEMS, timeout=3))
            remove_btn = item_element.find_element(*self.REMOVE_BUTTON)
            self.scroll_to(remove_btn)
            safe_click(self.driver, remove_btn)
            WebDriverWait(self.driver, 5).until(
                lambda d: len(d.find_elements(*self.CART_ITEMS)) < items_before
            )
            return True
        except:
            return False

    def remove_products(self, count=3):
        removed = []
        for _ in range(count):
            items = self.get_cart_items()
            if not items:
                break
            item = items[0]
            try:
                name = item.find_element(*self.PRODUCT_NAME).text
                if self.remove_product(item):
                    removed.append(name)
            except:
                continue
        return removed

    def proceed_to_checkout(self):
        try:
            checkout_btn = self.find_element(self.PROCEED_TO_CHECKOUT, timeout=2)
            self.scroll_to(checkout_btn)
            self.click(checkout_btn)
        except:
            self.driver.get(f"{self.base_url}?controller=order")
