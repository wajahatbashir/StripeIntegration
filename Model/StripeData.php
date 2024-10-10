<?php
namespace CI\StripeIntegration\Model;

use CI\StripeIntegration\Api\StripeDataInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\Exception;

class StripeData implements StripeDataInterface
{
    protected $scopeConfig; // For getting Stripe API key
    protected $curl; // For making HTTP requests
    protected $request; // For handling incoming request parameters

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        RequestInterface $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->request = $request;
    }

    public function getStripeData()
    {
        // Get customer email from the request parameter
        $email = $this->request->getParam('email');

        if (!$email) {
            return ['error' => 'Customer email is required.'];
        }

        // Get the Stripe API Key from the configuration
        $apiKey = $this->scopeConfig->getValue('ci_stripeintegration/general/api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        // Get customer details from Stripe
        $url = "https://api.stripe.com/v1/customers?email={$email}";
        $this->curl->setOption(CURLOPT_USERPWD, $apiKey . ":");
        $this->curl->get($url);
        $response = json_decode($this->curl->getBody(), true);

        if (!isset($response['data'][0]['id'])) {
            throw new Exception(__('Customer not found'), 404);
        }

        // Get customer ID from response
        $customerId = $response['data'][0]['id'];

        // Get subscription details from Stripe
        $url = "https://api.stripe.com/v1/subscriptions?customer={$customerId}";
        $this->curl->get($url);
        $subscriptionResponse = json_decode($this->curl->getBody(), true);

        // Check if there are no active subscriptions
        if (empty($subscriptionResponse['data'])) {
            return ['message' => 'No active subscriptions found for this customer.'];
        }

        // Check for canceled or inactive subscriptions
        $subscription = $subscriptionResponse['data'][0];
        if ($subscription['status'] !== 'active') {
            return ['message' => 'Subscription is canceled or inactive.'];
        }

        // Return the full subscription response if active
        return $subscriptionResponse;
    }
}
