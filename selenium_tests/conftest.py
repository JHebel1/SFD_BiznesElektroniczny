"""Pytest configuration and fixtures."""

import pytest
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.firefox.service import Service as FirefoxService
from webdriver_manager.chrome import ChromeDriverManager
from webdriver_manager.firefox import GeckoDriverManager
from config.config import Config


@pytest.fixture(scope="session")
def config():
    """Provide test configuration."""
    return Config


@pytest.fixture(scope="class")
def driver(config):
    """Create and configure WebDriver instance.

    Scope is 'class' so all tests in a test class share the same browser session.
    This allows tests to run sequentially with persistent state (e.g., cart contents).
    """
    if config.BROWSER == 'chrome':
        options = webdriver.ChromeOptions()
        if config.HEADLESS:
            options.add_argument('--headless')
        options.add_argument('--no-sandbox')
        options.add_argument('--disable-dev-shm-usage')
        options.add_argument('--window-size=1920,1080')
        options.add_argument('--start-maximized')
        options.add_argument('--disable-gpu')
        options.add_argument('--ignore-certificate-errors')
        options.add_argument('--ignore-ssl-errors')

        # PERFORMANCE OPTIMIZATIONS
        options.add_argument('--disable-extensions')
        options.add_argument('--disable-plugins')
        options.add_argument('--disable-popup-blocking')
        options.add_argument('--disable-translate')
        options.add_argument('--disable-background-timer-throttling')
        options.add_argument('--disable-renderer-backgrounding')
        options.add_argument('--disable-device-discovery-notifications')

        options.page_load_strategy = 'eager'

        prefs = {
            "download.default_directory": os.path.join(os.getcwd(), "downloads"),
            "download.prompt_for_download": False,
            # "profile.managed_default_content_settings.images": 2,
        }
        options.add_experimental_option("prefs", prefs)

        driver_path = ChromeDriverManager().install()

        if 'chromedriver-linux64' in driver_path and not driver_path.endswith('/chromedriver'):
            driver_dir = os.path.dirname(driver_path)
            actual_driver = os.path.join(driver_dir, 'chromedriver')
            if os.path.exists(actual_driver):
                driver_path = actual_driver

        if os.path.exists(driver_path):
            os.chmod(driver_path, 0o755)

        driver = webdriver.Chrome(
            service=ChromeService(driver_path),
            options=options
        )
    elif config.BROWSER == 'firefox':
        options = webdriver.FirefoxOptions()
        if config.HEADLESS:
            options.add_argument('--headless')
        options.add_argument('--width=1920')
        options.add_argument('--height=1080')

        driver = webdriver.Firefox(
            service=FirefoxService(GeckoDriverManager().install()),
            options=options
        )
    else:
        raise ValueError(f"Unsupported browser: {config.BROWSER}")

    driver.implicitly_wait(config.IMPLICIT_WAIT)
    driver.set_page_load_timeout(config.PAGE_LOAD_TIMEOUT)

    driver.maximize_window()

    yield driver

    driver.quit()


@pytest.fixture(scope="function")
def screenshot_on_failure(driver, request, config):
    """Take screenshot on test failure."""
    yield

    if request.node.rep_call.failed and config.SCREENSHOT_ON_FAILURE:
        if not os.path.exists(config.SCREENSHOTS_DIR):
            os.makedirs(config.SCREENSHOTS_DIR)

        test_name = request.node.name
        screenshot_path = os.path.join(
            config.SCREENSHOTS_DIR,
            f"failed_{test_name}.png"
        )
        driver.save_screenshot(screenshot_path)
        print(f"\nScreenshot saved to: {screenshot_path}")


@pytest.hookimpl(tryfirst=True, hookwrapper=True)
def pytest_runtest_makereport(item, call):
    """Make test result available to fixtures."""
    outcome = yield
    rep = outcome.get_result()
    setattr(item, f"rep_{rep.when}", rep)


@pytest.fixture(scope="session", autouse=True)
def setup_test_environment():
    """Set up test environment before running tests."""
    directories = ['screenshots', 'downloads', 'reports']
    for directory in directories:
        if not os.path.exists(directory):
            os.makedirs(directory)

    yield

    pass
