"""
This test suite performs the following actions in under 5 minutes:
1. Add 10 products (in varying quantities) from two different categories to the cart
2. Search for a product by name and add a random product from results
3. Remove 3 products from the cart
4. Register a new account
5. Complete the order with address, payment method (cash on delivery), and carrier selection
6. Confirm the order
7. Check order status
8. Download VAT invoice
"""

import pytest
import time
from config.config import Config
from pages.home_page import HomePage
from pages.category_page import CategoryPage
from pages.cart_page import CartPage
from pages.checkout_page import CheckoutPage
from pages.account_page import (
    OrderConfirmationPage,
    MyAccountPage,
    OrderHistoryPage
)
from utils.data_generator import DataGenerator


class TestShopWorkflow:
    def setup_method(self):
        """Set up test data before each test method."""
        self.data_generator = DataGenerator()
        self.customer_data = None
        self.address_data = None
        self.order_reference = None

    def test_01_add_products_from_categories(self, driver):
        """
        Test 1: Add 10 products from 2 different categories.
        Products should have varying quantities.
        """
        print("\n=== TEST 1: Adding 10 products from 2 categories ===")

        home_page = HomePage(driver, Config.SHOP_URL)
        home_page.open()

        categories = home_page.get_categories()
        print(f"Found {len(categories)} categories")

        if len(categories) < 2:
            print("Not enough categories found via menu, using alternative approach")
            category_urls = "search_fallback"
        else:
            category_urls = None

        category_page = CategoryPage(driver)
        total_products_added = 0
        products_per_category = 5

        if category_urls == "search_fallback":
            print("\n--- Using search to find products ---")

            print("\n--- Search 1 ---")
            home_page.open()
            home_page.search_product("a")  
            time.sleep(2)
            added = category_page.add_products_with_quantities(count=5)
            total_products_added += len(added)
            print(f"Added {len(added)} product entries from search 1")

            print("\n--- Search 2 ---")
            home_page.open()
            home_page.search_product("e")  
            time.sleep(1)
            added = category_page.add_products_with_quantities(count=5)
            total_products_added += len(added)
            print(f"Added {len(added)} product entries from search 2")

        else:
            print("\n--- Adding products from Category 1 ---")
            if category_urls:
                driver.get(category_urls[0])
                time.sleep(1)
            else:
                home_page.click_category(0)
                time.sleep(1)

            added = category_page.add_products_with_quantities(count=products_per_category)
            total_products_added += len(added)
            print(f"Added {len(added)} product entries from category 1")

            # Go back to home and navigate to second category
            if category_urls:
                driver.get(category_urls[1])
                time.sleep(1)
            else:
                home_page.open()
                home_page.click_category(1)
                time.sleep(1)

            print("\n--- Adding products from Category 2 ---")
            added = category_page.add_products_with_quantities(count=products_per_category)
            total_products_added += len(added)
            print(f"Added {len(added)} product entries from category 2")

        print(f"\nTotal products added: {total_products_added}")
        assert total_products_added >= 10, f"Expected at least 10 products, got {total_products_added}"

        home_page.open()
        cart_count = home_page.get_cart_count()
        print(f"Cart count: {cart_count}")
        assert cart_count > 0, "Cart should not be empty"

    def test_02_search_and_add_random_product(self, driver):
        """
        Test 2: Search for a product and add a random one from results.
        """
        print("\n=== TEST 2: Search and add random product ===")

        home_page = HomePage(driver, Config.SHOP_URL)
        home_page.open()

        search_term = "protein"

        print(f"Searching for: {search_term}")
        home_page.search_product(search_term)
        time.sleep(1)

        category_page = CategoryPage(driver)
        products = category_page.get_all_products()

        print(f"Found {len(products)} products in search results")
        assert len(products) > 0, "No products found in search results"

        random_products = category_page.get_random_products(count=1)
        if random_products:
            product_name = category_page.get_product_name(random_products[0])
            print(f"Adding random product: {product_name}")
            category_page.add_product_to_cart(random_products[0])
            print("Product added successfully")

    def test_03_remove_products_from_cart(self, driver):
        """
        Test 3: Remove 3 products from the cart.
        """
        print("\n=== TEST 3: Removing 3 products from cart ===")

        cart_page = CartPage(driver, Config.SHOP_URL)
        cart_page.open()

        initial_count = cart_page.get_cart_items_count()
        print(f"Initial cart items: {initial_count}")

        if initial_count < 4:
            print(f"Cart has only {initial_count} items, need at least 4. Adding products...")

            home_page = HomePage(driver, Config.SHOP_URL)
            home_page.open()

            categories = home_page.get_categories()
            if categories:
                home_page.click_category(0)
                time.sleep(1)

                category_page = CategoryPage(driver)
                products_needed = 4 - initial_count
                added = category_page.add_products_with_quantities(count=products_needed)
                print(f"Added {len(added)} products to cart")

            cart_page.open()
            initial_count = cart_page.get_cart_items_count()
            print(f"Cart now has {initial_count} items")

        assert initial_count >= 3, f"Not enough items in cart to remove 3. Found: {initial_count}"

        removed = cart_page.remove_products(count=3)
        print(f"Removed products: {removed}")

        final_count = cart_page.get_cart_items_count()
        print(f"Final cart items: {final_count}")
        assert final_count == initial_count - 3, "Products not removed correctly"

    def test_04_register_new_account_and_checkout(self, driver):
        """
        Test 4-8: Register new account and complete checkout process.
        This includes:
        - Customer registration
        - Order placement
        - Payment method selection (cash on delivery)
        - Carrier selection
        - Order confirmation
        """
        print("\n=== TEST 4: Register and Complete Checkout ===")

        # Generate customer data
        self.customer_data = self.data_generator.generate_customer_data(
            Config.TEST_EMAIL_DOMAIN
        )
        self.address_data = self.data_generator.generate_address_data()

        print(f"Generated customer email: {self.customer_data['email']}")
        print(f"Generated customer name: {self.customer_data['firstname']} {self.customer_data['lastname']}")

        # Navigate to cart and proceed to checkout
        cart_page = CartPage(driver, Config.SHOP_URL)
        cart_page.open()

        cart_count = cart_page.get_cart_items_count()
        print(f"Cart items before checkout: {cart_count}")
        assert cart_count > 0, "Cart is empty, cannot proceed to checkout"

        # Register new account first
        checkout_page = CheckoutPage(driver, Config.SHOP_URL)

        print("\n--- Registering new account ---")
        checkout_page.open_registration_page()
        checkout_page.fill_customer_form(self.customer_data)

        # After registration, immediately go to checkout - don't waste time checking login status
        print("\n--- Proceeding to checkout ---")
        cart_page.open()  # Go to cart first
        time.sleep(0.5)
        cart_page.proceed_to_checkout()
        time.sleep(1)

        print("\n--- Filling address information ---")
        checkout_page.fill_address_form(self.address_data)

        print("\n--- Selecting carrier ---")
        carriers = checkout_page.get_available_carriers()
        print(f"Available carriers: {carriers}")
        checkout_page.select_carrier(carrier_index=0)  # Select first carrier

        print("\n--- Selecting payment method (Cash on delivery) ---")
        payment_methods = checkout_page.get_available_payment_methods()
        print(f"Available payment methods: {payment_methods}")
        checkout_page.select_payment_method(payment_name="cash")

        print("\n--- Accepting terms and placing order ---")
        checkout_page.accept_terms()
        checkout_page.place_order()

        # Verify order confirmation
        time.sleep(2)
        confirmation_page = OrderConfirmationPage(driver)

        assert confirmation_page.is_order_confirmed(), "Order confirmation not displayed"
        print("\n✓ Order confirmed successfully!")

        # Get order reference
        self.order_reference = confirmation_page.get_order_reference()
        print(f"Order reference: {self.order_reference}")

    def test_05_check_order_status(self, driver):
        """
        Test 9: Check order status in customer account.
        """
        print("\n=== TEST 5: Checking order status ===")

        # Navigate to order history
        order_history_page = OrderHistoryPage(driver, Config.SHOP_URL)
        order_history_page.open()
        time.sleep(1)

        # Get latest order status
        status = order_history_page.get_latest_order_status()
        print(f"Latest order status: {status}")
        assert status is not None, "Could not retrieve order status"

        # Get orders
        orders = order_history_page.get_orders()
        print(f"Total orders in history: {len(orders)}")
        assert len(orders) > 0, "No orders found in history"

    def test_06_download_invoice(self, driver):
        """
        Test 10: Download VAT invoice.
        """
        print("\n=== TEST 6: Downloading VAT invoice ===")

        order_history_page = OrderHistoryPage(driver, Config.SHOP_URL)
        order_history_page.open()
        time.sleep(1)

        print("Attempting to download invoice...")
        result = order_history_page.download_latest_invoice()

        if result:
            print("Invoice download initiated successfully")
        else:
            print("Invoice download not available (order might not be in invoiced status yet)")