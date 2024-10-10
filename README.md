# CI_StripeIntegration

## Overview
CI_StripeIntegration is a custom Magento 2 module that integrates with Stripe to retrieve customer and subscription information based on a customer’s email. It provides Admin Panel configuration to set the Stripe API Key and allows the Magento store to interact with Stripe APIs for customer and subscription management.

### Key Features:
- Fetches customer information from Stripe using the customer’s email.
- Retrieves subscription details, including status, period start and end dates, and metadata.
- Provides clear error handling for cases like missing customers, no active subscriptions, or canceled/inactive subscriptions.
- Configurable through the Magento Admin Panel for enabling/disabling and setting the Stripe API Key.

## Installation

1. **Download/Clone** the module into the `app/code/CI/StripeIntegration` directory:
   ```bash
   mkdir -p app/code/CI/StripeIntegration
   ```

2. **Enable the module** by running the following commands from the Magento root directory:
   ```bash
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:flush
   ```

3. **Admin Configuration**:
   - Navigate to `Stores > Configuration > Sales > Stripe Integration` in the Magento Admin Panel.
   - Enable the module and enter your Stripe API Key.

## API Usage

The module exposes a GET API endpoint that allows you to fetch customer and subscription data from Stripe.

### API Endpoint:

- **URL**: 
  ```
  GET http://<your-magento-store-url>/rest/V1/stripe/info?email=customer@example.com
  ```
  Replace `customer@example.com` with the actual customer email.

### Response Scenarios:

1. **When the customer exists and has an active subscription**:
   - The API will return the full subscription details as received from Stripe.

2. **When the customer exists but has no active subscriptions**:
   ```json
   {
       "message": "No active subscriptions found for this customer."
   }
   ```

3. **When the customer has a canceled or inactive subscription**:
   ```json
   {
       "message": "Subscription is canceled or inactive."
   }
   ```

4. **When the customer does not exist in Stripe**:
   ```json
   {
       "message": "Customer not found."
   }
   ```

### Example Response for Active Subscription:

```json
{
    "id": "sub_1PRdk3Aq4v0Z90tcK0TR13jI",
    "status": "active",
    "cancel_at_period_end": false,
    "current_period_start": "2024-09-15 17:16:47",
    "current_period_end": "2024-10-15 17:16:47",
    "metadata": {
        "Customer ID": "774",
        "Module": "Stripe Payments M2 v1.4.1",
        "Order #": "233000032357",
        "Product ID": "1378",
        "Trial": "1 days"
    }
}
```

## Requirements
- Magento 2.4.x
- PHP 7.4 or 8.x
- Valid Stripe API Key (live or test)

## Support
For any issues or questions, feel free to open an issue in the repository or contact the module author.

## License
This module is open-source and licensed under the [OSL 3.0 License](https://opensource.org/licenses/OSL-3.0).