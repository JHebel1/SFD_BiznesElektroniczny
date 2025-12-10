"""Debug script to inspect category structure on the website."""

import time
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service as ChromeService
from webdriver_manager.chrome import ChromeDriverManager
from config.config import Config

def inspect_categories():
    """Inspect the HTML structure to find categories."""
    # Set up driver (same as conftest.py)
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--window-size=1920,1080')
    options.add_argument('--ignore-certificate-errors')
    options.add_argument('--ignore-ssl-errors')

    # Install and get chromedriver path
    driver_path = ChromeDriverManager().install()

    # Fix for webdriver-manager bug: ensure we have the actual chromedriver executable
    if 'chromedriver-linux64' in driver_path and not driver_path.endswith('/chromedriver'):
        driver_dir = os.path.dirname(driver_path)
        actual_driver = os.path.join(driver_dir, 'chromedriver')
        if os.path.exists(actual_driver):
            driver_path = actual_driver

    # Ensure chromedriver has execute permissions
    if os.path.exists(driver_path):
        os.chmod(driver_path, 0o755)

    driver = webdriver.Chrome(
        service=ChromeService(driver_path),
        options=options
    )

    try:
        print(f"Opening {Config.SHOP_URL}")
        driver.get(Config.SHOP_URL)
        time.sleep(5)  # Wait for page to load

        # Try to close any popups
        try:
            popup_close = driver.find_element(By.CSS_SELECTOR, ".popup-close, .close, button[aria-label='Close']")
            popup_close.click()
            time.sleep(1)
        except:
            print("No popup to close")

        print("\n" + "="*80)
        print("INSPECTING MENU STRUCTURE")
        print("="*80)

        # Print entire body HTML (first 5000 chars)
        body = driver.find_element(By.TAG_NAME, "body")
        print("\n--- Body HTML (first 5000 chars) ---")
        print(body.get_attribute('outerHTML')[:5000])

        # Check for various menu structures
        selectors_to_inspect = [
            ("Main nav", By.CSS_SELECTOR, "nav"),
            ("Header", By.CSS_SELECTOR, "header"),
            ("Desktop menu by ID", By.ID, "_desktop_top_menu"),
            ("Top menu by ID", By.ID, "top-menu"),
            ("Menu class", By.CSS_SELECTOR, ".menu"),
            ("Category links", By.CSS_SELECTOR, "a[href*='categor']"),
            ("Category links alt", By.CSS_SELECTOR, "a[href*='id_category']"),
            ("All nav links", By.CSS_SELECTOR, "nav a"),
            ("Menu items", By.CSS_SELECTOR, ".menu-item a, .nav-item a"),
        ]

        for name, by, selector in selectors_to_inspect:
            try:
                elements = driver.find_elements(by, selector)
                print(f"\n--- {name}: {selector} ---")
                print(f"Found {len(elements)} element(s)")

                if elements:
                    for i, elem in enumerate(elements[:5]):  # Show first 5
                        try:
                            print(f"  [{i}] Tag: {elem.tag_name}")
                            print(f"      Text: {elem.text[:50]}")
                            print(f"      Href: {elem.get_attribute('href')}")
                            print(f"      Classes: {elem.get_attribute('class')}")
                            print(f"      HTML: {elem.get_attribute('outerHTML')[:200]}")
                        except:
                            pass
            except Exception as e:
                print(f"\n--- {name}: {selector} ---")
                print(f"Error: {e}")

        print("\n" + "="*80)
        print("PAGE SOURCE (first 10000 chars)")
        print("="*80)
        print(driver.page_source[:10000])

    finally:
        driver.quit()

if __name__ == "__main__":
    inspect_categories()
