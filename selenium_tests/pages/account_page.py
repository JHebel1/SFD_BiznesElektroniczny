import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from pages.base_page import BasePage


class OrderConfirmationPage(BasePage):

    CONFIRMATION_MESSAGE = (By.CSS_SELECTOR, "#content-hook_order_confirmation, .order-confirmation")
    ORDER_REFERENCE = (By.CSS_SELECTOR, "#order-reference-value, .order-reference")

    def __init__(self, driver):
        super().__init__(driver)

    def is_order_confirmed(self):
        return self.is_displayed(self.CONFIRMATION_MESSAGE, timeout=10)

    def get_order_reference(self):
        try:
            return self.find_element(self.ORDER_REFERENCE, timeout=5).text
        except:
            return None


class OrderHistoryPage(BasePage):

    ORDER_ROWS = (By.CSS_SELECTOR, ".table-striped tbody tr, #order-list tbody tr")
    ORDER_STATUS = (By.CSS_SELECTOR, ".label-pill, .order-status")
    INVOICE_LINK = (By.CSS_SELECTOR, "a[href*='invoice'], .invoice-download")

    def __init__(self, driver, base_url):
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        self.driver.get(f"{self.base_url}?controller=history")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "#main, .page-content"))
        )
        return self

    def get_orders(self):
        try:
            return self.find_elements(self.ORDER_ROWS, timeout=5)
        except:
            return []

    def get_latest_order(self):
        orders = self.get_orders()
        return orders[0] if orders else None

    def get_order_status(self, order_element):
        try:
            return order_element.find_element(*self.ORDER_STATUS).text
        except:
            return None

    def get_latest_order_status(self):
        latest = self.get_latest_order()
        return self.get_order_status(latest) if latest else None

    def download_latest_invoice(self):
        latest = self.get_latest_order()
        if latest:
            try:
                invoice_link = latest.find_element(*self.INVOICE_LINK)
                if invoice_link and invoice_link.is_displayed():
                    self.click(invoice_link)
                    time.sleep(1)
                    return True
            except:
                pass
        return False
