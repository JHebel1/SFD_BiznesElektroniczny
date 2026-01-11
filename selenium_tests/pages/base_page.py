from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from utils.helpers import wait_for_element, wait_for_elements, safe_click, scroll_to_element


class BasePage:

    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def close_popups(self):
        try:
            self.driver.execute_script("""
                var popup = document.getElementById('posnewsletterpopup');
                var overlay = document.getElementById('posnewsletterpopup-overlay');
                if (popup) popup.style.display = 'none';
                if (overlay) overlay.style.display = 'none';
                var cookiePopup = document.getElementById('poscookielaw');
                if (cookiePopup) cookiePopup.style.display = 'none';
                document.querySelectorAll('.modal, .popup, .newsletter-modal').forEach(el => {
                    el.style.display = 'none';
                    el.classList.remove('show', 'in');
                });
                document.querySelectorAll('.modal-backdrop, .popup-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = 'auto';
            """)
        except:
            pass

    def find_element(self, locator, timeout=10):
        return wait_for_element(self.driver, locator, timeout)

    def find_elements(self, locator, timeout=10):
        return wait_for_elements(self.driver, locator, timeout)

    def click(self, element):
        safe_click(self.driver, element)

    def scroll_to(self, element):
        scroll_to_element(self.driver, element)

    def is_displayed(self, locator, timeout=5):
        try:
            element = self.find_element(locator, timeout)
            return element.is_displayed()
        except TimeoutException:
            return False
