import random
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pages.base_page import BasePage
from utils.helpers import safe_click


class CategoryPage(BasePage):

    PRODUCT_LIST = (By.ID, "js-product-list")
    PRODUCT_ARTICLE = (By.CSS_SELECTOR, "#js-product-list article.product-miniature")
    PRODUCT_LINK = (By.CSS_SELECTOR, "a.product_name, a.product-thumbnail")
    PRODUCT_NAME_ATTR = "title"

    QUANTITY_INPUT = (By.ID, "quantity_wanted")
    ADD_TO_CART_BTN = (By.CSS_SELECTOR, "button.add-to-cart[data-button-action='add-to-cart']")
    CART_MODAL = (By.ID, "blockcart-modal")
    CART_MODAL_CONTINUE = (By.CSS_SELECTOR, "#blockcart-modal button.btn-secondary, #blockcart-modal .cart-content-btn button")

    def __init__(self, driver):
        super().__init__(driver)

    def get_all_products(self):
        try:
            WebDriverWait(self.driver, 5).until(
                EC.presence_of_element_located(self.PRODUCT_LIST)
            )
        except:
            return []

        self.close_popups()

        self.driver.implicitly_wait(0)
        try:
            for selector in [
                "article[data-id-product]",
                ".js-product-miniature",
                ".product-miniature",
            ]:
                products = self.driver.find_elements(By.CSS_SELECTOR, selector)
                if products:
                    return products
        finally:
            self.driver.implicitly_wait(10)

        return []

    def _get_product_urls(self):
        products = self.get_all_products()
        urls = []

        self.driver.implicitly_wait(0)
        try:
            for product in products:
                try:
                    href = None
                    name = None

                    for link_selector in ["a.product_name", "h3 a", "a.product-thumbnail"]:
                        try:
                            link = product.find_element(By.CSS_SELECTOR, link_selector)
                            href = link.get_attribute('href')
                            name = link.get_attribute('title') or link.text.strip()
                            if href and name:
                                break
                        except:
                            continue

                    if not name:
                        try:
                            img = product.find_element(By.CSS_SELECTOR, "img")
                            name = img.get_attribute('alt')
                        except:
                            pass

                    if not href:
                        try:
                            link = product.find_element(By.CSS_SELECTOR, "a[href*='/']")
                            href = link.get_attribute('href')
                        except:
                            pass

                    if href and name:
                        urls.append({'url': href, 'name': name})
                except:
                    continue
        finally:
            self.driver.implicitly_wait(10)

        return urls

    def _close_cart_modal(self):
        try:
            WebDriverWait(self.driver, 3).until(
                EC.visibility_of_element_located(self.CART_MODAL)
            )
            try:
                continue_btn = self.driver.find_element(*self.CART_MODAL_CONTINUE)
                safe_click(self.driver, continue_btn)
            except:
                self.driver.execute_script("""
                    var modal = document.getElementById('blockcart-modal');
                    if (modal) { modal.style.display = 'none'; modal.classList.remove('show'); }
                    var backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                    document.body.classList.remove('modal-open');
                """)
            return True
        except:
            return False

    def _add_from_product_page(self, quantity):
        self.driver.implicitly_wait(0)
        try:
            add_btn = self.driver.find_element(*self.ADD_TO_CART_BTN)
            if add_btn.get_attribute('disabled'):
                return False
        except:
            return False
        finally:
            self.driver.implicitly_wait(10)

        self.close_popups()

        try:
            qty_input = self.driver.find_element(*self.QUANTITY_INPUT)

            max_qty = None
            self.driver.implicitly_wait(0)
            try:
                for attr in ['max', 'data-stock']:
                    val = qty_input.get_attribute(attr)
                    if val and val.isdigit():
                        max_qty = int(val)
                        break

                if not max_qty:
                    stock_el = self.driver.find_element(By.CSS_SELECTOR, "[data-stock]")
                    stock_val = stock_el.get_attribute('data-stock')
                    if stock_val and stock_val.isdigit():
                        max_qty = int(stock_val)
            except:
                pass
            finally:
                self.driver.implicitly_wait(10)

            if max_qty:
                if max_qty < 1:
                    return False
                if quantity > max_qty:
                    quantity = max_qty

            self.driver.execute_script("""
                var input = arguments[0];
                input.value = arguments[1];
                input.dispatchEvent(new Event('change', { bubbles: true }));
            """, qty_input, str(quantity))

            safe_click(self.driver, add_btn)
            self._close_cart_modal()
            return True
        except:
            return False

    def add_products_from_category(self, count=5):
        category_url = self.driver.current_url
        added = []
        added_names = set()

        product_data = self._get_product_urls()
        if not product_data:
            return added

        random.shuffle(product_data)

        for product in product_data:
            if len(added) >= count:
                break

            name = product['name']
            if name in added_names:
                continue

            self.driver.get(product['url'])
            quantity = random.randint(1, 2)

            if self._add_from_product_page(quantity):
                added.append({'name': name, 'quantity': quantity})
                added_names.add(name)

            self.driver.get(category_url)

        return added

    def get_product_name(self, product_element):
        try:
            link = product_element.find_element(*self.PRODUCT_LINK)
            return link.get_attribute('title') or link.text.strip()
        except:
            return ""

    def add_product_to_cart(self, product_element):
        category_url = self.driver.current_url
        try:
            link = product_element.find_element(*self.PRODUCT_LINK)
            self.driver.get(link.get_attribute('href'))
            result = self._add_from_product_page(random.randint(1, 2))
            self.driver.get(category_url)
            return result
        except:
            self.driver.get(category_url)
            return False

    def get_random_products(self, count=1):
        products = self.get_all_products()
        if not products:
            return []
        return random.sample(products, min(count, len(products)))
