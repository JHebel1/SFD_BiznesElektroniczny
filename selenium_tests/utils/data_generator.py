"""Generate random test data using Faker."""

from faker import Faker
import random
import string
from datetime import datetime


class DataGenerator:
    """Generate random test data for automated testing."""

    def __init__(self, locale='pl_PL'):
        """Initialize Faker with specified locale."""
        self.fake = Faker(locale)

    def generate_email(self, domain=None):
        """Generate a unique email address."""
        timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
        random_string = ''.join(random.choices(string.ascii_lowercase, k=5))
        domain = domain or 'testmail.com'
        return f"test_{timestamp}_{random_string}@{domain}"

    def generate_password(self, length=12):
        """Generate a secure random password that meets PrestaShop requirements."""
        # PrestaShop typically requires: 8+ chars, mix of upper, lower, and digits
        # Avoiding special characters that might cause issues
        # Ensure at least 1 uppercase, 1 lowercase, 1 digit
        password = (
            random.choice(string.ascii_uppercase) +  # At least 1 uppercase
            random.choice(string.ascii_lowercase) +  # At least 1 lowercase
            random.choice(string.digits) +          # At least 1 digit
            ''.join(random.choices(string.ascii_letters + string.digits, k=length - 3))
        )
        # Shuffle to avoid predictable pattern
        password_list = list(password)
        random.shuffle(password_list)
        return ''.join(password_list)

    def generate_first_name(self):
        """Generate a random first name."""
        return self.fake.first_name()

    def generate_last_name(self):
        """Generate a random last name."""
        return self.fake.last_name()

    def generate_company(self):
        """Generate a random company name."""
        return self.fake.company()

    def generate_address(self):
        """Generate a random street address."""
        return self.fake.street_address()

    def generate_city(self):
        """Generate a random city name."""
        return self.fake.city()

    def generate_postcode(self):
        """Generate a random postal code."""
        return self.fake.postcode()

    def generate_phone(self):
        """Generate a random phone number."""
        return self.fake.phone_number()

    def generate_customer_data(self, email_domain=None):
        """Generate complete customer registration data."""
        return {
            'gender': random.choice([1, 2]),  # 1=Mr, 2=Mrs
            'firstname': self.generate_first_name(),
            'lastname': self.generate_last_name(),
            'email': self.generate_email(email_domain),
            'password': self.generate_password(),
            'birthday': self.fake.date_of_birth(minimum_age=18, maximum_age=80).strftime('%Y-%m-%d'),
        }

    def generate_address_data(self):
        """Generate complete address data."""
        return {
            'alias': 'My address',
            'company': self.generate_company(),
            'address1': self.generate_address(),
            'address2': f'Apt {random.randint(1, 999)}' if random.choice([True, False]) else '',
            'postcode': self.generate_postcode(),
            'city': self.generate_city(),
            'phone': self.generate_phone(),
        }
