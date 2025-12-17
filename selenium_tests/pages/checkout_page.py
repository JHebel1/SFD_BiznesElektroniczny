"""Checkout page object."""

import time
from selenium.webdriver.common.by import By
from selenium.webdriver.support.select import Select
from pages.base_page import BasePage
from utils.helpers import wait_for_element, wait_for_clickable, fill_input, safe_click


class CheckoutPage(BasePage):
    """Checkout page object."""

    # Personal Information / Guest Checkout
    GUEST_CHECKOUT_TAB = (By.CSS_SELECTOR, "a[href='#checkout-guest-form']")
    CUSTOMER_FORM = (By.ID, "customer-form")
    GENDER_MR = (By.ID, "field-id_gender-1")
    GENDER_MRS = (By.ID, "field-id_gender-2")
    FIRST_NAME = (By.ID, "field-firstname")
    LAST_NAME = (By.ID, "field-lastname")
    EMAIL = (By.ID, "field-email")
    PASSWORD = (By.ID, "field-password")
    BIRTHDAY = (By.ID, "field-birthday")
    NEWSLETTER = (By.NAME, "newsletter")
    PRIVACY_TERMS = (By.NAME, "psgdpr")
    CUSTOMER_PRIVACY = (By.NAME, "customer_privacy")
    CONTINUE_BUTTON = (By.NAME, "continue")

    # Address Form
    ADDRESS_FORM = (By.ID, "delivery-address")
    ADDRESS_ALIAS = (By.ID, "field-alias")
    ADDRESS_COMPANY = (By.ID, "field-company")
    ADDRESS_VAT = (By.ID, "field-vat_number")
    ADDRESS_LINE1 = (By.ID, "field-address1")
    ADDRESS_LINE2 = (By.ID, "field-address2")
    ADDRESS_POSTCODE = (By.ID, "field-postcode")
    ADDRESS_CITY = (By.ID, "field-city")
    ADDRESS_COUNTRY = (By.ID, "field-id_country")
    ADDRESS_PHONE = (By.ID, "field-phone")
    CONFIRM_ADDRESSES_BUTTON = (By.NAME, "confirm-addresses")

    # Shipping Method
    DELIVERY_OPTIONS = (By.CSS_SELECTOR, ".delivery-option")
    DELIVERY_OPTION_RADIO = (By.CSS_SELECTOR, "input[name^='delivery_option']")
    CARRIER_NAME = (By.CSS_SELECTOR, ".carrier-name")
    DELIVERY_MESSAGE = (By.ID, "delivery_message")
    CONFIRM_DELIVERY_BUTTON = (By.NAME, "confirmDeliveryOption")

    # Payment Method
    PAYMENT_OPTIONS = (By.CSS_SELECTOR, ".payment-option")
    PAYMENT_OPTION_RADIO = (By.CSS_SELECTOR, "input[name='payment-option']")
    PAYMENT_OPTION_LABEL = (By.CSS_SELECTOR, ".payment-option label")
    TERMS_CHECKBOX = (By.ID, "conditions_to_approve[terms-and-conditions]")
    PLACE_ORDER_BUTTON = (By.CSS_SELECTOR, "#payment-confirmation button")

    def __init__(self, driver, base_url):
        """Initialize checkout page."""
        super().__init__(driver)
        self.base_url = base_url

    def _close_cookie_popup(self):
        """Close cookie law popup if present."""
        try:
            # Try to close the cookie popup using JavaScript
            self.driver.execute_script("""
                // Hide cookie law popup
                var cookiePopup = document.getElementById('poscookielaw');
                if (cookiePopup) {
                    cookiePopup.style.display = 'none';
                }
                // Also try clicking accept button if present
                var acceptBtn = document.querySelector('#poscookielaw .accept, #poscookielaw button, .cookie-accept');
                if (acceptBtn) acceptBtn.click();
            """)
            time.sleep(0.2)
        except:
            pass

    def open(self):
        """Open checkout page."""
        checkout_url = f"{self.base_url}?controller=order"
        self.driver.get(checkout_url)
        time.sleep(2)
        return self

    def open_registration_page(self):
        """Navigate to registration page."""
        # PrestaShop registration is at /login?create_account=1
        registration_url = f"{self.base_url.rstrip('/')}/login?create_account=1"
        print(f"Opening registration page: {registration_url}")
        self.driver.get(registration_url)
        time.sleep(1)
        return self

    def is_logged_in(self):
        """Check if user is logged in."""
        import time
        time.sleep(1)  # Wait for page to update after login

        try:
            # Check URL - if we're on account page, we're logged in
            current_url = self.driver.current_url
            if 'my-account' in current_url or 'account' in current_url:
                print(f"User is logged in (on account page: {current_url})")
                return True

            # Try to find logout link or user account indicators
            logout_selectors = [
                (By.CSS_SELECTOR, "a[href*='logout']"),
                (By.CSS_SELECTOR, ".logout"),
                (By.CSS_SELECTOR, ".user-info a[href*='account']"),
                (By.CSS_SELECTOR, ".account a[href*='my-account']"),
                (By.XPATH, "//a[contains(@href, 'logout')]"),
                (By.XPATH, "//a[contains(text(), 'Sign out')]"),
                (By.XPATH, "//a[contains(text(), 'Wyloguj')]"),  # Polish
                (By.XPATH, "//a[contains(text(), 'My account')]"),
                (By.XPATH, "//a[contains(text(), 'Moje konto')]"),  # Polish
            ]

            for selector in logout_selectors:
                try:
                    element = self.find_element(selector, timeout=1)
                    if element and element.is_displayed():
                        print(f"User is logged in (found element: {selector})")
                        return True
                except:
                    continue

            # Check if we're NOT on the login page (another indicator)
            if 'login' not in current_url:
                # We're not on login page and not on account page
                # This might mean we're logged in but on a different page
                print(f"? Possibly logged in (not on login page, on: {current_url})")
                # Let's check for user info in header
                try:
                    user_info = self.driver.find_element(By.CSS_SELECTOR, ".user-info, .account, .header-user")
                    if user_info:
                        print("User is logged in (found user info in header)")
                        return True
                except:
                    pass

            print(f"User is NOT logged in (current URL: {current_url})")
            return False
        except Exception as e:
            print(f"Error checking login status: {e}")
            return False

    def login_with_credentials(self, email, password):
        """Login with email and password if not already logged in."""
        if self.is_logged_in():
            print("Already logged in, skipping login")
            return True

        print(f"Logging in with email: {email}")
        login_url = f"{self.base_url.rstrip('/')}/login"
        self.driver.get(login_url)
        time.sleep(1)

        # Fill email
        email_selectors = [
            (By.ID, "field-email"),
            (By.NAME, "email"),
            (By.CSS_SELECTOR, "input[type='email']"),
        ]
        for selector in email_selectors:
            try:
                email_input = self.find_element(selector, timeout=2)
                from utils.helpers import fill_input
                fill_input(self.driver, email_input, email)
                break
            except:
                continue

        # Fill password
        password_selectors = [
            (By.ID, "field-password"),
            (By.NAME, "password"),
            (By.CSS_SELECTOR, "input[type='password']"),
        ]
        for selector in password_selectors:
            try:
                password_input = self.find_element(selector, timeout=2)
                from utils.helpers import fill_input
                fill_input(self.driver, password_input, password)
                break
            except:
                continue

        # Click login button
        login_button_selectors = [
            (By.ID, "submit-login"),
            (By.CSS_SELECTOR, "button[type='submit']"),
            (By.CSS_SELECTOR, ".btn-primary[type='submit']"),
            (By.CSS_SELECTOR, "#submit-login"),
            (By.XPATH, "//button[@type='submit']"),
        ]
        for selector in login_button_selectors:
            try:
                login_btn = self.find_element(selector, timeout=2)
                self.scroll_to(login_btn)
                self.click(login_btn)
                print(f"Login button clicked with selector: {selector}")

                # Wait for redirect/page load after login
                time.sleep(3)

                # Check if login was successful
                if self.is_logged_in():
                    print("Login successful!")
                    return True
                else:
                    print("Login button clicked but still not logged in")
                    # Try next selector
                    continue
            except Exception as e:
                print(f"Could not click login with selector {selector}: {e}")
                continue

        print("Could not find login button or login failed")
        return False

    def select_guest_checkout(self):
        """Select guest checkout option."""
        try:
            guest_tab = self.find_element(self.GUEST_CHECKOUT_TAB, timeout=5)
            self.click(guest_tab)
            time.sleep(1)
        except:
            # Guest checkout might be default or not available
            pass

    def fill_customer_form(self, customer_data):
        """Fill customer registration form (works for both registration and checkout)."""
        import time

        print(f"Filling registration form with:")
        print(f"  Email: {customer_data['email']}")
        print(f"  Password: {customer_data['password']}")
        print(f"  Name: {customer_data['firstname']} {customer_data['lastname']}")

        # Try multiple selectors for each field (registration page vs checkout page)

        # Select gender
        try:
            if customer_data.get('gender') == 1:
                gender_selectors = [
                    (By.ID, "field-id_gender-1"),
                    (By.CSS_SELECTOR, "input[name='id_gender'][value='1']"),
                ]
            else:
                gender_selectors = [
                    (By.ID, "field-id_gender-2"),
                    (By.CSS_SELECTOR, "input[name='id_gender'][value='2']"),
                ]

            for selector in gender_selectors:
                try:
                    gender_radio = self.find_element(selector, timeout=2)
                    self.click(gender_radio)
                    break
                except:
                    continue
        except:
            print("Could not set gender")

        # Fill first name
        for selector in [(By.ID, "field-firstname"), (By.NAME, "firstname")]:
            try:
                first_name_input = self.find_element(selector, timeout=2)
                fill_input(self.driver, first_name_input, customer_data['firstname'])
                break
            except:
                continue

        # Fill last name
        for selector in [(By.ID, "field-lastname"), (By.NAME, "lastname")]:
            try:
                last_name_input = self.find_element(selector, timeout=2)
                fill_input(self.driver, last_name_input, customer_data['lastname'])
                break
            except:
                continue

        # Fill email
        for selector in [(By.ID, "field-email"), (By.NAME, "email")]:
            try:
                email_input = self.find_element(selector, timeout=2)
                fill_input(self.driver, email_input, customer_data['email'])
                break
            except:
                continue

        # Fill password
        for selector in [(By.ID, "field-password"), (By.NAME, "password"), (By.CSS_SELECTOR, "input[type='password']")]:
            try:
                password_input = self.find_element(selector, timeout=2)
                fill_input(self.driver, password_input, customer_data['password'])
                print(f"  Password filled")

                # Some forms require password confirmation
                try:
                    password_confirm = self.driver.find_element(By.CSS_SELECTOR, "input[name='password_confirmation'], input[name='confirm-password']")
                    fill_input(self.driver, password_confirm, customer_data['password'])
                    print(f"  Password confirmation filled")
                except:
                    pass

                break
            except:
                continue

        # Fill birthday if field exists
        try:
            birthday_input = self.find_element(self.BIRTHDAY, timeout=2)
            fill_input(self.driver, birthday_input, customer_data.get('birthday', ''))
        except:
            pass

        # Close any popups (like cookie law) that might be blocking elements
        self.close_popups()
        self._close_cookie_popup()

        # Accept terms (try multiple selectors) - use JavaScript for checkboxes that might be obscured
        privacy_selectors = [
            (By.NAME, "customer_privacy"),
            (By.ID, "customer_privacy"),
            (By.CSS_SELECTOR, "input[name='customer_privacy']"),
        ]
        for selector in privacy_selectors:
            try:
                privacy_checkbox = self.find_element(selector, timeout=2)
                if not privacy_checkbox.is_selected():
                    # Use JavaScript click to avoid click interception
                    self.driver.execute_script("arguments[0].click();", privacy_checkbox)
                    print("  Privacy checkbox checked")
                break
            except:
                continue

        psgdpr_selectors = [
            (By.NAME, "psgdpr"),
            (By.ID, "psgdpr"),
            (By.CSS_SELECTOR, "input[name='psgdpr']"),
        ]
        for selector in psgdpr_selectors:
            try:
                psgdpr_checkbox = self.find_element(selector, timeout=2)
                if not psgdpr_checkbox.is_selected():
                    # Use JavaScript click to avoid click interception
                    self.driver.execute_script("arguments[0].click();", psgdpr_checkbox)
                    print("  PSGDPR checkbox checked")
                break
            except:
                continue

        # Click submit button
        try:
            submit_btn = self.find_element((By.CSS_SELECTOR, "#customer-form button.form-control-submit"), timeout=2)
            self.scroll_to(submit_btn)
            self.click(submit_btn)
            time.sleep(1)
            print("Registration form submitted successfully")
        except Exception as e:
            print(f"Could not click submit button: {e}")

    def fill_address_form(self, address_data):
        """Fill address form."""
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC

        # Make sure we're on the checkout page
        current_url = self.driver.current_url
        if 'order' not in current_url:
            print(f"Not on checkout page, navigating there. Current URL: {current_url}")
            checkout_url = f"{self.base_url}?controller=order"
            self.driver.get(checkout_url)
            time.sleep(1)

        # Close any popups first
        self.close_popups()
        self._close_cookie_popup()

        # Wait for address form to appear (checkout step)
        print("Waiting for address form...")
        try:
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#delivery-address, .address-form, [id*='address']"))
            )
        except:
            print(f"Address form not found, current URL: {self.driver.current_url}")
            # Try scrolling down to reveal the form
            self.driver.execute_script("window.scrollTo(0, 300);")
            time.sleep(0.5)

        # Address line 1 (required)
        address1_input = self.find_element(self.ADDRESS_LINE1)
        fill_input(self.driver, address1_input, address_data['address1'])

        # Address line 2 (optional)
        if address_data.get('address2'):
            try:
                address2_input = self.find_element(self.ADDRESS_LINE2, timeout=2)
                fill_input(self.driver, address2_input, address_data['address2'])
            except:
                pass

        # City
        city_input = self.find_element(self.ADDRESS_CITY)
        fill_input(self.driver, city_input, address_data['city'])

        # Postcode
        postcode_input = self.find_element(self.ADDRESS_POSTCODE)
        fill_input(self.driver, postcode_input, address_data['postcode'])

        # Country
        try:
            country_select = self.find_element(self.ADDRESS_COUNTRY)
            select = Select(country_select)
            # Select first available country (usually Poland for pl_PL locale)
            select.select_by_index(1)
            time.sleep(1)  # Wait for state/province to load if applicable
        except:
            pass

        # Phone
        phone_input = self.find_element(self.ADDRESS_PHONE)
        fill_input(self.driver, phone_input, address_data.get('phone', '123456789'))

        # Company (optional)
        if address_data.get('company'):
            try:
                company_input = self.find_element(self.ADDRESS_COMPANY, timeout=2)
                fill_input(self.driver, company_input, address_data['company'])
            except:
                pass

        # Confirm addresses
        confirm_btn = self.find_element(self.CONFIRM_ADDRESSES_BUTTON)
        self.scroll_to(confirm_btn)
        self.click(confirm_btn)
        time.sleep(0.5)

    def select_carrier(self, carrier_index=0):
        """Select a carrier/shipping method."""
        carrier_radios = self.find_elements(self.DELIVERY_OPTION_RADIO)

        if carrier_radios and len(carrier_radios) > carrier_index:
            carrier_radio = carrier_radios[carrier_index]
            self.scroll_to(carrier_radio)
            self.click(carrier_radio)
            time.sleep(0.3)

        # Confirm delivery option
        try:
            confirm_btn = self.find_element(self.CONFIRM_DELIVERY_BUTTON, timeout=3)
            self.scroll_to(confirm_btn)
            self.click(confirm_btn)
            time.sleep(0.5)
        except:
            pass

    def get_available_carriers(self):
        """Get list of available carriers."""
        carriers = []
        try:
            carrier_elements = self.find_elements(self.DELIVERY_OPTIONS)
            for carrier in carrier_elements:
                try:
                    name_element = carrier.find_element(*self.CARRIER_NAME)
                    carriers.append(name_element.text)
                except:
                    continue
        except:
            pass
        return carriers

    def select_payment_method(self, payment_index=0, payment_name=None):
        """Select a payment method."""
        payment_radios = self.find_elements(self.PAYMENT_OPTION_RADIO)

        if payment_name:
            # Select by name (e.g., "Cash on delivery")
            payment_labels = self.find_elements(self.PAYMENT_OPTION_LABEL)
            for i, label in enumerate(payment_labels):
                if payment_name.lower() in label.text.lower():
                    payment_index = i
                    break

        if payment_radios and len(payment_radios) > payment_index:
            payment_radio = payment_radios[payment_index]
            self.scroll_to(payment_radio)
            self.click(payment_radio)
            time.sleep(0.3)

    def get_available_payment_methods(self):
        """Get list of available payment methods."""
        methods = []
        try:
            payment_labels = self.find_elements(self.PAYMENT_OPTION_LABEL)
            for label in payment_labels:
                methods.append(label.text)
        except:
            pass
        return methods

    def accept_terms(self):
        """Accept terms and conditions."""
        try:
            terms_checkbox = self.find_element(self.TERMS_CHECKBOX)
            if not terms_checkbox.is_selected():
                self.scroll_to(terms_checkbox)
                self.click(terms_checkbox)
        except Exception as e:
            print(f"Error accepting terms: {e}")

    def place_order(self):
        """Click place order button."""
        from selenium.webdriver.support.ui import WebDriverWait
        from selenium.webdriver.support import expected_conditions as EC

        place_order_btn = wait_for_clickable(self.driver, self.PLACE_ORDER_BUTTON)
        self.scroll_to(place_order_btn)
        self.click(place_order_btn)

        # Wait for confirmation page
        try:
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.CSS_SELECTOR, "#content-hook_order_confirmation, .order-confirmation, [id*='confirmation']"))
            )
        except:
            time.sleep(2)  # Fallback

    def complete_checkout(self, customer_data, address_data, carrier_index=0, payment_method="cash"):
        """Complete entire checkout process."""
        # Step 1: Fill customer information
        self.select_guest_checkout()
        self.fill_customer_form(customer_data)

        # Step 2: Fill address
        self.fill_address_form(address_data)

        # Step 3: Select carrier
        self.select_carrier(carrier_index)

        # Step 4: Select payment and place order
        self.select_payment_method(payment_name=payment_method)
        self.accept_terms()
        self.place_order()
