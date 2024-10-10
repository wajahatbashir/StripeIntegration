<?php
namespace CI\StripeIntegration\Api;

interface StripeDataInterface
{
    /**
     * Retrieve customer and subscription details from Stripe.
     *
     * @return string
     */
    public function getStripeData();
}
