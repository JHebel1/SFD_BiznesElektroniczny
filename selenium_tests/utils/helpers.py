"""Helper functions for Selenium tests."""

import time
import os
from datetime import datetime
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException, NoSuchElementException


def wait_for_element(driver, locator, timeout=10):
    """Wait for element to be present and return it."""
    try:
        element = WebDriverWait(driver, timeout).until(
            EC.presence_of_element_located(locator)
        )
        return element
    except TimeoutException:
        raise TimeoutException(f"Element {locator} not found within {timeout} seconds")


def wait_for_clickable(driver, locator, timeout=10):
    """Wait for element to be clickable and return it."""
    try:
        element = WebDriverWait(driver, timeout).until(
            EC.element_to_be_clickable(locator)
        )
        return element
    except TimeoutException:
        raise TimeoutException(f"Element {locator} not clickable within {timeout} seconds")


def wait_for_elements(driver, locator, timeout=10):
    """Wait for multiple elements to be present and return them."""
    try:
        elements = WebDriverWait(driver, timeout).until(
            EC.presence_of_all_elements_located(locator)
        )
        return elements
    except TimeoutException:
        raise TimeoutException(f"Elements {locator} not found within {timeout} seconds")


def safe_click(driver, element, timeout=10):
    """Safely click an element with wait for clickability."""
    try:
        WebDriverWait(driver, timeout).until(EC.element_to_be_clickable(element))
        element.click()
    except Exception as e:
        # Try JavaScript click as fallback
        driver.execute_script("arguments[0].click();", element)


def scroll_to_element(driver, element):
    """Scroll to make element visible."""
    driver.execute_script("arguments[0].scrollIntoView({behavior: 'auto', block: 'center'});", element)
    time.sleep(0.2)  # Reduced wait for instant scroll


def is_element_present(driver, locator, timeout=5):
    """Check if element is present on the page."""
    try:
        WebDriverWait(driver, timeout).until(
            EC.presence_of_element_located(locator)
        )
        return True
    except TimeoutException:
        return False


def take_screenshot(driver, name, directory='screenshots'):
    """Take a screenshot and save it with timestamp."""
    if not os.path.exists(directory):
        os.makedirs(directory)

    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filename = f"{name}_{timestamp}.png"
    filepath = os.path.join(directory, filename)

    driver.save_screenshot(filepath)
    return filepath


def wait_for_page_load(driver, timeout=10):
    """Wait for page to fully load."""
    WebDriverWait(driver, timeout).until(
        lambda d: d.execute_script('return document.readyState') == 'complete'
    )


def switch_to_iframe(driver, iframe_locator, timeout=10):
    """Switch to iframe."""
    WebDriverWait(driver, timeout).until(
        EC.frame_to_be_available_and_switch_to_it(iframe_locator)
    )


def get_element_text(driver, locator, timeout=10):
    """Get text from element."""
    element = wait_for_element(driver, locator, timeout)
    return element.text


def select_dropdown_by_text(driver, select_element, text):
    """Select dropdown option by visible text."""
    from selenium.webdriver.support.select import Select
    select = Select(select_element)
    select.select_by_visible_text(text)


def select_dropdown_by_value(driver, select_element, value):
    """Select dropdown option by value."""
    from selenium.webdriver.support.select import Select
    select = Select(select_element)
    select.select_by_value(value)


def wait_and_clear_input(driver, element, timeout=10):
    """Clear input field safely."""
    WebDriverWait(driver, timeout).until(EC.element_to_be_clickable(element))
    element.clear()
    time.sleep(0.2)


def fill_input(driver, element, text, clear_first=True):
    """Fill input field with text."""
    if clear_first:
        wait_and_clear_input(driver, element)
    element.send_keys(text)
    time.sleep(0.1)


def wait_for_url_contains(driver, url_part, timeout=10):
    """Wait for URL to contain specific text."""
    WebDriverWait(driver, timeout).until(EC.url_contains(url_part))


def wait_for_url_to_be(driver, url, timeout=10):
    """Wait for URL to be exactly as specified."""
    WebDriverWait(driver, timeout).until(EC.url_to_be(url))


def hover_over_element(driver, element):
    """Hover mouse over element."""
    from selenium.webdriver.common.action_chains import ActionChains
    actions = ActionChains(driver)
    actions.move_to_element(element).perform()
    time.sleep(0.3)
