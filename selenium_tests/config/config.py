"""Configuration management for Selenium tests."""

import os
from dotenv import load_dotenv

load_dotenv()


class Config:
    """Test configuration settings."""

    SHOP_URL = os.getenv('SHOP_URL', 'http://localhost:8080')

    BROWSER = os.getenv('BROWSER', 'chrome').lower()
    HEADLESS = os.getenv('HEADLESS', 'false').lower() == 'true'

    IMPLICIT_WAIT = int(os.getenv('TIMEOUT', '10'))
    EXPLICIT_WAIT = int(os.getenv('TIMEOUT', '10'))
    PAGE_LOAD_TIMEOUT = 10

    SCREENSHOT_ON_FAILURE = os.getenv('SCREENSHOT_ON_FAILURE', 'true').lower() == 'true'
    SCREENSHOTS_DIR = 'screenshots'

    TEST_EMAIL_DOMAIN = os.getenv('TEST_EMAIL_DOMAIN', 'testmail.com')

    @classmethod
    def get_url(cls, path=''):
        """Get full URL for a given path."""
        return f"{cls.SHOP_URL.rstrip('/')}/{path.lstrip('/')}" if path else cls.SHOP_URL
