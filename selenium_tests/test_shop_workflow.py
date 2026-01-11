from config.config import Config
from pages.home_page import HomePage
from pages.category_page import CategoryPage
from pages.cart_page import CartPage
from pages.checkout_page import CheckoutPage
from pages.account_page import OrderConfirmationPage, OrderHistoryPage
from utils.data_generator import DataGenerator


class TestShopWorkflow:

    def setup_method(self):
        self.data_generator = DataGenerator()
        self.customer_data = None
        self.address_data = None

    def test_01_add_products_from_categories(self, driver):
        home_page = HomePage(driver, Config.SHOP_URL)
        home_page.open()

        categories = home_page.get_categories()
        print(f"Found {len(categories)} categories")

        category_page = CategoryPage(driver)
        total_added = 0

        if len(categories) >= 2:
            home_page.click_category(0)
            added = category_page.add_products_from_category(count=5)
            total_added += len(added)
            print(f"Category 1: added {len(added)} products")
            for p in added:
                print(f"  - {p['name']}")

            home_page.open()
            home_page.click_category(1)
            added = category_page.add_products_from_category(count=5)
            total_added += len(added)
            print(f"Category 2: added {len(added)} products")
            for p in added:
                print(f"  - {p['name']}")
        else:
            home_page.search_product("biako")
            added = category_page.add_products_from_category(count=5)
            total_added += len(added)

            home_page.open()
            home_page.search_product("kreatyna")
            added = category_page.add_products_from_category(count=5)
            total_added += len(added)

        print(f"Total products added: {total_added}")
        assert total_added >= 10

        home_page.open()
        assert home_page.get_cart_count() > 0

    def test_02_search_and_add_random_product(self, driver):
        home_page = HomePage(driver, Config.SHOP_URL)
        home_page.open()
        home_page.search_product("protein")

        category_page = CategoryPage(driver)
        products = category_page.get_all_products()
        assert len(products) > 0

        random_products = category_page.get_random_products(count=1)
        if random_products:
            name = category_page.get_product_name(random_products[0])
            print(f"Adding product: {name}")
            category_page.add_product_to_cart(random_products[0])

    def test_03_remove_products_from_cart(self, driver):
        cart_page = CartPage(driver, Config.SHOP_URL)
        cart_page.open()

        initial_count = cart_page.get_cart_items_count()
        print(f"Initial cart items: {initial_count}")

        if initial_count < 4:
            home_page = HomePage(driver, Config.SHOP_URL)
            home_page.open()
            categories = home_page.get_categories()
            if categories:
                home_page.click_category(0)
                CategoryPage(driver).add_products_from_category(count=4 - initial_count)
            cart_page.open()
            initial_count = cart_page.get_cart_items_count()

        assert initial_count >= 3
        removed = cart_page.remove_products(count=3)
        print(f"Removed: {removed}")

        final_count = cart_page.get_cart_items_count()
        assert final_count == initial_count - 3

    def test_04_register_new_account_and_checkout(self, driver):
        self.customer_data = self.data_generator.generate_customer_data(Config.TEST_EMAIL_DOMAIN)
        self.address_data = self.data_generator.generate_address_data()
        print(f"Customer: {self.customer_data['email']}")

        cart_page = CartPage(driver, Config.SHOP_URL)
        cart_page.open()
        assert cart_page.get_cart_items_count() > 0

        checkout_page = CheckoutPage(driver, Config.SHOP_URL)
        checkout_page.open_registration_page()
        checkout_page.fill_customer_form(self.customer_data)

        cart_page.open()
        cart_page.proceed_to_checkout()

        checkout_page.fill_address_form(self.address_data)

        carriers = checkout_page.get_available_carriers()
        print(f"Carriers: {carriers}")
        checkout_page.select_carrier(carrier_index=0)

        payments = checkout_page.get_available_payment_methods()
        print(f"Payment methods: {payments}")
        checkout_page.select_payment_method(payment_name="cash")

        checkout_page.accept_terms()
        checkout_page.place_order()

        confirmation_page = OrderConfirmationPage(driver)
        assert confirmation_page.is_order_confirmed()
        print(f"Order reference: {confirmation_page.get_order_reference()}")

    def test_05_check_order_status(self, driver):
        order_history = OrderHistoryPage(driver, Config.SHOP_URL)
        order_history.open()

        status = order_history.get_latest_order_status()
        print(f"Order status: {status}")
        assert status is not None

        orders = order_history.get_orders()
        assert len(orders) > 0

    def test_06_download_invoice(self, driver):
        order_history = OrderHistoryPage(driver, Config.SHOP_URL)
        order_history.open()

        result = order_history.download_latest_invoice()
        assert result, "Invoice download failed"
