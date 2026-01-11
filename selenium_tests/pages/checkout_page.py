import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.select import Select
from pages.base_page import BasePage
from utils.helpers import fill_input, wait_for_clickable


class CheckoutPage(BasePage):

    GENDER_MR = (By.ID, "field-id_gender-1")
    GENDER_MRS = (By.ID, "field-id_gender-2")
    FIRST_NAME = (By.ID, "field-firstname")
    LAST_NAME = (By.ID, "field-lastname")
    EMAIL = (By.ID, "field-email")
    PASSWORD = (By.ID, "field-password")
    BIRTHDAY = (By.ID, "field-birthday")
    CUSTOMER_PRIVACY = (By.NAME, "customer_privacy")
    PSGDPR = (By.NAME, "psgdpr")
    SUBMIT_BUTTON = (By.CSS_SELECTOR, "#customer-form button.form-control-submit")

    ADDRESS_LINE1 = (By.ID, "field-address1")
    ADDRESS_LINE2 = (By.ID, "field-address2")
    ADDRESS_POSTCODE = (By.ID, "field-postcode")
    ADDRESS_CITY = (By.ID, "field-city")
    ADDRESS_COUNTRY = (By.ID, "field-id_country")
    ADDRESS_PHONE = (By.ID, "field-phone")
    ADDRESS_COMPANY = (By.ID, "field-company")
    CONFIRM_ADDRESSES = (By.NAME, "confirm-addresses")

    DELIVERY_OPTION_RADIO = (By.CSS_SELECTOR, "input[name^='delivery_option']")
    DELIVERY_OPTIONS = (By.CSS_SELECTOR, ".delivery-option")
    CARRIER_NAME = (By.CSS_SELECTOR, ".carrier-name")
    CONFIRM_DELIVERY = (By.NAME, "confirmDeliveryOption")

    PAYMENT_OPTION_RADIO = (By.CSS_SELECTOR, "input[name='payment-option']")
    PAYMENT_OPTION_LABEL = (By.CSS_SELECTOR, ".payment-option label")
    TERMS_CHECKBOX = (By.CSS_SELECTOR, "input[id*='terms-and-conditions']")
    PLACE_ORDER = (By.CSS_SELECTOR, "#payment-confirmation button")

    def __init__(self, driver, base_url):
        super().__init__(driver)
        self.base_url = base_url

    def open_registration_page(self):
        self.driver.get(f"{self.base_url.rstrip('/')}/login?create_account=1")
        WebDriverWait(self.driver, 5).until(
            EC.presence_of_element_located((By.ID, "customer-form"))
        )
        return self

    def fill_customer_form(self, customer_data):
        try:
            gender_selector = self.GENDER_MR if customer_data.get('gender') == 1 else self.GENDER_MRS
            self.driver.execute_script("arguments[0].click();", self.find_element(gender_selector, timeout=2))
        except:
            pass

        fill_input(self.driver, self.find_element(self.FIRST_NAME), customer_data['firstname'])
        fill_input(self.driver, self.find_element(self.LAST_NAME), customer_data['lastname'])
        fill_input(self.driver, self.find_element(self.EMAIL), customer_data['email'])
        fill_input(self.driver, self.find_element(self.PASSWORD), customer_data['password'])

        try:
            fill_input(self.driver, self.find_element(self.BIRTHDAY, timeout=2), customer_data.get('birthday', ''))
        except:
            pass

        self.close_popups()

        for selector in [self.CUSTOMER_PRIVACY, self.PSGDPR]:
            try:
                checkbox = self.find_element(selector, timeout=2)
                if not checkbox.is_selected():
                    self.driver.execute_script("arguments[0].click();", checkbox)
            except:
                pass

        try:
            self.click(self.find_element(self.SUBMIT_BUTTON, timeout=2))
        except:
            pass

    def fill_address_form(self, address_data):
        if 'order' not in self.driver.current_url:
            self.driver.get(f"{self.base_url}?controller=order")

        self.close_popups()

        WebDriverWait(self.driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "#delivery-address, .address-form"))
        )

        fill_input(self.driver, self.find_element(self.ADDRESS_LINE1), address_data['address1'])

        if address_data.get('address2'):
            try:
                fill_input(self.driver, self.find_element(self.ADDRESS_LINE2, timeout=2), address_data['address2'])
            except:
                pass

        fill_input(self.driver, self.find_element(self.ADDRESS_CITY), address_data['city'])
        fill_input(self.driver, self.find_element(self.ADDRESS_POSTCODE), address_data['postcode'])

        try:
            country_select = self.find_element(self.ADDRESS_COUNTRY)
            Select(country_select).select_by_index(1)
        except:
            pass

        fill_input(self.driver, self.find_element(self.ADDRESS_PHONE), address_data.get('phone', '123456789'))

        if address_data.get('company'):
            try:
                fill_input(self.driver, self.find_element(self.ADDRESS_COMPANY, timeout=2), address_data['company'])
            except:
                pass

        self.scroll_to(self.find_element(self.CONFIRM_ADDRESSES))
        self.click(self.find_element(self.CONFIRM_ADDRESSES))

    def select_carrier(self, carrier_index=0):
        self.driver.execute_script("""
            var radios = document.querySelectorAll('input[name^="delivery_option"]');
            if (radios[arguments[0]]) radios[arguments[0]].click();
        """, carrier_index)

        self.driver.execute_script("""
            var btn = document.querySelector('button[name="confirmDeliveryOption"]');
            if (btn) btn.click();
        """)

    def get_available_carriers(self):
        carriers = []
        try:
            for carrier in self.find_elements(self.DELIVERY_OPTIONS):
                try:
                    carriers.append(carrier.find_element(*self.CARRIER_NAME).text)
                except:
                    pass
        except:
            pass
        return carriers

    def select_payment_method(self, payment_index=0, payment_name=None):
        if payment_name and "cash" in payment_name.lower():
            self.driver.execute_script("""
                var radio = document.querySelector('input[data-module-name="ps_cashondelivery"]');
                if (radio) radio.click();
            """)
        else:
            self.driver.execute_script("""
                var radios = document.querySelectorAll('.payment-option input[type="radio"]');
                if (radios[arguments[0]]) radios[arguments[0]].click();
            """, payment_index)

    def get_available_payment_methods(self):
        try:
            return [label.text for label in self.find_elements(self.PAYMENT_OPTION_LABEL)]
        except:
            return []

    def accept_terms(self):
        self.driver.execute_script("""
            var cb = document.querySelector('#conditions-to-approve input[type="checkbox"]');
            if (cb && !cb.checked) cb.click();
        """)

    def place_order(self):
        place_order_btn = wait_for_clickable(self.driver, self.PLACE_ORDER)
        self.scroll_to(place_order_btn)
        self.click(place_order_btn)
        try:
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#content-hook_order_confirmation, .order-confirmation"))
            )
        except:
            time.sleep(2)
