from faker import Faker
import random
import string
from datetime import datetime


class DataGenerator:

    def __init__(self, locale='pl_PL'):
        self.fake = Faker(locale)

    def generate_email(self, domain=None):
        timestamp = datetime.now().strftime('%Y%m%d%H%M%S')
        random_string = ''.join(random.choices(string.ascii_lowercase, k=5))
        domain = domain or 'testmail.com'
        return f"test_{timestamp}_{random_string}@{domain}"

    def generate_password(self, length=12):
        password = (
            random.choice(string.ascii_uppercase) +
            random.choice(string.ascii_lowercase) +
            random.choice(string.digits) +
            ''.join(random.choices(string.ascii_letters + string.digits, k=length - 3))
        )
        password_list = list(password)
        random.shuffle(password_list)
        return ''.join(password_list)

    def generate_customer_data(self, email_domain=None):
        return {
            'gender': random.choice([1, 2]),
            'firstname': self.fake.first_name(),
            'lastname': self.fake.last_name(),
            'email': self.generate_email(email_domain),
            'password': self.generate_password(),
            'birthday': self.fake.date_of_birth(minimum_age=18, maximum_age=80).strftime('%Y-%m-%d'),
        }

    def generate_address_data(self):
        return {
            'alias': 'My address',
            'company': self.fake.company(),
            'address1': self.fake.street_address(),
            'address2': f'Apt {random.randint(1, 999)}' if random.choice([True, False]) else '',
            'postcode': self.fake.postcode(),
            'city': self.fake.city(),
            'phone': self.fake.phone_number(),
        }
