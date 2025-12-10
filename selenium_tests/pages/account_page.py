"""Customer account page objects."""

import time
from selenium.webdriver.common.by import By
from pages.base_page import BasePage
from utils.helpers import wait_for_element


class OrderConfirmationPage(BasePage):
    """Order confirmation page object."""

    # Locators
    CONFIRMATION_MESSAGE = (By.CSS_SELECTOR, "#content-hook_order_confirmation, .order-confirmation")
    ORDER_REFERENCE = (By.CSS_SELECTOR, "#order-reference-value, .order-reference")
    ORDER_DETAILS = (By.ID, "order-details")
    ORDER_ITEMS = (By.CSS_SELECTOR, ".order-confirmation-table")

    def __init__(self, driver):
        """Initialize order confirmation page."""
        super().__init__(driver)

    def is_order_confirmed(self):
        """Check if order confirmation is displayed."""
        return self.is_displayed(self.CONFIRMATION_MESSAGE, timeout=10)

    def get_order_reference(self):
        """Get order reference number."""
        try:
            order_ref_element = self.find_element(self.ORDER_REFERENCE, timeout=5)
            return order_ref_element.text
        except:
            return None


class MyAccountPage(BasePage):
    """My Account page object."""

    # Locators
    ORDER_HISTORY_LINK = (By.CSS_SELECTOR, "#history-link, a[href*='history']")
    ADDRESSES_LINK = (By.CSS_SELECTOR, "#addresses-link, a[href*='addresses']")
    IDENTITY_LINK = (By.CSS_SELECTOR, "#identity-link, a[href*='identity']")
    ACCOUNT_LINKS = (By.CSS_SELECTOR, ".link-item a")

    def __init__(self, driver, base_url):
        """Initialize my account page."""
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        """Open my account page."""
        account_url = f"{self.base_url}?controller=my-account"
        self.driver.get(account_url)
        time.sleep(1)
        return self

    def go_to_order_history(self):
        """Navigate to order history."""
        order_history_link = self.find_element(self.ORDER_HISTORY_LINK)
        self.click(order_history_link)
        time.sleep(1)


class OrderHistoryPage(BasePage):
    """Order history page object."""

    # Locators
    ORDER_TABLE = (By.CSS_SELECTOR, ".table-striped, #order-list")
    ORDER_ROWS = (By.CSS_SELECTOR, ".table-striped tbody tr, #order-list tbody tr")
    ORDER_REFERENCE = (By.CSS_SELECTOR, ".order-reference")
    ORDER_STATUS = (By.CSS_SELECTOR, ".label-pill, .order-status")
    ORDER_TOTAL = (By.CSS_SELECTOR, ".total-price")
    DETAILS_LINK = (By.CSS_SELECTOR, "[data-link-action='view-order-details'], .view-order")
    INVOICE_LINK = (By.CSS_SELECTOR, "a[href*='invoice'], .invoice-download")

    def __init__(self, driver, base_url):
        """Initialize order history page."""
        super().__init__(driver)
        self.base_url = base_url

    def open(self):
        """Open order history page."""
        history_url = f"{self.base_url}?controller=history"
        self.driver.get(history_url)
        time.sleep(1)
        return self

    def get_orders(self):
        """Get all order rows."""
        try:
            return self.find_elements(self.ORDER_ROWS, timeout=5)
        except:
            return []

    def get_latest_order(self):
        """Get the most recent order."""
        orders = self.get_orders()
        if orders:
            return orders[0]
        return None

    def get_order_status(self, order_element):
        """Get status of an order."""
        try:
            status_element = order_element.find_element(*self.ORDER_STATUS)
            return status_element.text
        except:
            return None

    def get_order_reference_from_row(self, order_element):
        """Get order reference from order row."""
        try:
            ref_element = order_element.find_element(*self.ORDER_REFERENCE)
            return ref_element.text
        except:
            return None

    def click_order_details(self, order_element):
        """Click on order details link."""
        try:
            details_link = order_element.find_element(*self.DETAILS_LINK)
            self.click(details_link)
            time.sleep(1)
            return True
        except:
            return False

    def download_invoice(self, order_element):
        """Download invoice for an order."""
        try:
            invoice_link = order_element.find_element(*self.INVOICE_LINK)
            self.click(invoice_link)
            time.sleep(2)  # Wait for download
            return True
        except:
            return False

    def get_latest_order_status(self):
        """Get status of the most recent order."""
        latest_order = self.get_latest_order()
        if latest_order:
            return self.get_order_status(latest_order)
        return None

    def download_latest_invoice(self):
        """Download invoice for the most recent order (if available)."""
        latest_order = self.get_latest_order()
        if latest_order:
            # Check if invoice link exists
            try:
                invoice_link = latest_order.find_element(*self.INVOICE_LINK)
                if invoice_link and invoice_link.is_displayed():
                    self.click(invoice_link)
                    time.sleep(1)
                    return True
                else:
                    print("Invoice link exists but not visible")
                    return False
            except:
                print("No invoice available for this order (normal for new/unpaid orders)")
                return False
        return False


class OrderDetailPage(BasePage):
    """Order detail page object."""

    # Locators
    ORDER_REFERENCE = (By.CSS_SELECTOR, ".order-reference")
    ORDER_STATUS = (By.CSS_SELECTOR, ".label, .order-status")
    ORDER_PRODUCTS = (By.CSS_SELECTOR, ".order-products")
    INVOICE_LINK = (By.CSS_SELECTOR, "a[href*='invoice']")
    REORDER_LINK = (By.CSS_SELECTOR, "[data-link-action='add-to-cart']")

    def __init__(self, driver):
        """Initialize order detail page."""
        super().__init__(driver)

    def get_order_reference(self):
        """Get order reference."""
        try:
            ref_element = self.find_element(self.ORDER_REFERENCE)
            return ref_element.text
        except:
            return None

    def get_order_status(self):
        """Get order status."""
        try:
            status_element = self.find_element(self.ORDER_STATUS)
            return status_element.text
        except:
            return None

    def download_invoice(self):
        """Download invoice."""
        try:
            invoice_link = self.find_element(self.INVOICE_LINK)
            self.click(invoice_link)
            time.sleep(2)  # Wait for download
            return True
        except:
            return False
