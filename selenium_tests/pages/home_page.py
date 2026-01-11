from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pages.base_page import BasePage


class HomePage(BasePage):

    SEARCH_INPUT = (By.CSS_SELECTOR, "input[name='s']")
    CART_COUNT = (By.CSS_SELECTOR, ".cart-products-count")
    CATEGORY_LINKS = (By.CSS_SELECTOR, ".category-sub-menu li a, .submenu-item > a[href*='localhost']")

    def __init__(self, driver, base_url):
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        self.driver.get(self.base_url)
        try:
            WebDriverWait(self.driver, 3).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#_desktop_megamenu, .pos-menu-horizontal"))
            )
            self.driver.execute_script("window.stop();")
        except:
            pass
        self.close_popups()
        return self

    def search_product(self, search_term):
        search_input = self.find_element(self.SEARCH_INPUT, timeout=2)
        search_input.clear()
        search_input.send_keys(search_term)
        search_input.send_keys(Keys.RETURN)

    def get_categories(self):
        try:
            links = self.find_elements(self.CATEGORY_LINKS, timeout=2)
            valid_links = []
            seen_hrefs = set()
            for link in links:
                href = link.get_attribute('href')
                if href and 'javascript:void' not in href and href not in seen_hrefs:
                    if href not in (self.base_url, self.base_url + '/'):
                        valid_links.append(link)
                        seen_hrefs.add(href)
            return valid_links[:10]
        except:
            return []

    def click_category(self, index=0):
        categories = self.get_categories()
        if categories and len(categories) > index:
            href = categories[index].get_attribute('href')
            self.driver.get(href)
            return True
        return False

    def get_cart_count(self):
        try:
            cart_count_element = self.find_element(self.CART_COUNT, timeout=2)
            count_text = cart_count_element.text.strip()
            return int(''.join(filter(str.isdigit, count_text)))
        except:
            return 0
