"""Base page object with common functionality."""

from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException
from utils.helpers import (
    wait_for_element, wait_for_clickable, wait_for_elements,
    safe_click, scroll_to_element, take_screenshot
)


class BasePage:
    """Base page object that all page objects inherit from."""

    def __init__(self, driver):
        """Initialize base page with driver."""
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def close_popups(self):
        """Close any popups, modals, or overlays that might block interaction - optimized."""
        # Use JavaScript immediately - fastest and most reliable method
        try:
            self.driver.execute_script("""
                // Hide pospopup newsletter
                var popup = document.getElementById('posnewsletterpopup');
                var overlay = document.getElementById('posnewsletterpopup-overlay');
                if (popup) popup.style.display = 'none';
                if (overlay) overlay.style.display = 'none';

                // Hide cookie law popup
                var cookiePopup = document.getElementById('poscookielaw');
                if (cookiePopup) cookiePopup.style.display = 'none';

                // Hide all other modals
                document.querySelectorAll('.modal, .popup, .newsletter-modal').forEach(el => {
                    el.style.display = 'none';
                    el.classList.remove('show', 'in');
                });

                // Remove modal backdrop
                document.querySelectorAll('.modal-backdrop, .popup-backdrop').forEach(el => {
                    el.remove();
                });

                // Remove body modal classes
                document.body.classList.remove('modal-open');
                document.body.style.overflow = 'auto';
            """)
        except:
            pass

    def find_element(self, locator, timeout=10):
        """Find element with wait."""
        return wait_for_element(self.driver, locator, timeout)

    def find_elements(self, locator, timeout=10):
        """Find multiple elements with wait."""
        return wait_for_elements(self.driver, locator, timeout)

    def click(self, element):
        """Click element safely."""
        safe_click(self.driver, element)

    def scroll_to(self, element):
        """Scroll to element."""
        scroll_to_element(self.driver, element)

    def get_text(self, locator, timeout=10):
        """Get text from element."""
        element = self.find_element(locator, timeout)
        return element.text

    def is_displayed(self, locator, timeout=5):
        """Check if element is displayed."""
        try:
            element = self.find_element(locator, timeout)
            return element.is_displayed()
        except TimeoutException:
            return False

    def take_screenshot(self, name):
        """Take screenshot."""
        return take_screenshot(self.driver, name)

    def get_current_url(self):
        """Get current page URL."""
        return self.driver.current_url

    def wait_for_url_contains(self, url_part, timeout=10):
        """Wait for URL to contain specific text."""
        WebDriverWait(self.driver, timeout).until(EC.url_contains(url_part))
