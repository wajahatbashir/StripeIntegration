<?php
namespace CI\StripeIntegration\Controller\V1;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use CI\StripeIntegration\Api\StripeDataInterface;

class StripeInfo extends Action
{
    protected $stripeData;

    public function __construct(
        Context $context,
        StripeDataInterface $stripeData
    ) {
        parent::__construct($context);
        $this->stripeData = $stripeData;
    }

    public function execute()
    {
        $result = $this->stripeData->getStripeData();
        $this->getResponse()->representJson($result);
    }
}
