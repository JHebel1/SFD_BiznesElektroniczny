from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException


def wait_for_element(driver, locator, timeout=10):
    try:
        return WebDriverWait(driver, timeout).until(EC.presence_of_element_located(locator))
    except TimeoutException:
        raise TimeoutException(f"Element {locator} not found within {timeout} seconds")


def wait_for_clickable(driver, locator, timeout=10):
    try:
        return WebDriverWait(driver, timeout).until(EC.element_to_be_clickable(locator))
    except TimeoutException:
        raise TimeoutException(f"Element {locator} not clickable within {timeout} seconds")


def wait_for_elements(driver, locator, timeout=10):
    try:
        return WebDriverWait(driver, timeout).until(EC.presence_of_all_elements_located(locator))
    except TimeoutException:
        raise TimeoutException(f"Elements {locator} not found within {timeout} seconds")


def safe_click(driver, element, timeout=10):
    try:
        WebDriverWait(driver, timeout).until(EC.element_to_be_clickable(element))
        element.click()
    except:
        driver.execute_script("arguments[0].click();", element)


def scroll_to_element(driver, element):
    driver.execute_script("arguments[0].scrollIntoView({behavior: 'auto', block: 'center'});", element)


def fill_input(driver, element, text, clear_first=True):
    if clear_first:
        WebDriverWait(driver, 10).until(EC.element_to_be_clickable(element))
        element.clear()
    element.send_keys(text)


def take_screenshot(driver, name, directory='screenshots'):
    import os
    from datetime import datetime
    if not os.path.exists(directory):
        os.makedirs(directory)
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    filepath = os.path.join(directory, f"{name}_{timestamp}.png")
    driver.save_screenshot(filepath)
    return filepath
