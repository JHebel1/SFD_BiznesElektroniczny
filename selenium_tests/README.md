# PrestaShop Selenium Automated Tests

This test suite provides automated testing for the PrestaShop e-commerce platform using Selenium WebDriver.

## Requirements

- Python 3.8+
- Chrome browser
- ChromeDriver or GeckoDriver (will be auto-installed via webdriver-manager)

## Installation

**Easy way:** Just run the script :D
```bash
./run_tests.sh
```

## Test Workflow

The complete test workflow includes:

1. **Adding Products**: Add 10 products from 2 different categories with varying quantities
2. **Product Search**: Search for a product and add a random item from results
3. **Cart Management**: Remove 3 products from the cart
4. **User Registration**: Create a new customer account
5. **Checkout Process**: Complete order with address, carrier, and payment selection
6. **Order Confirmation**: Verify order was placed successfully
7. **Order Status**: Check order status in customer account
8. **Invoice Download**: Download VAT invoice for the order

## Test Duration

The complete test suite is designed to complete in under 5 minutes.

