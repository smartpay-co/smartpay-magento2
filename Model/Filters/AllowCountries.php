<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\Filters;

use Magento\Quote\Api\Data\CartInterface;
use Smartpay\Smartpay\Gateway\Settings;

class AllowCountries implements FilterInterface
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * AllowCountries constructor.
     * @param Settings $settings
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Settings $settings,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * @param CartInterface $quote
     * @return bool
     */
    public function execute(CartInterface $quote): bool
    {
        $quoteBillingCountry = $quote->getBillingAddress()->getCountryId();
        if ($quoteBillingCountry && $quoteBillingCountry != 'JP') {
            $this->warning("[Smartpay] Filters/AllowCountries: \$quoteBillingCountry != JP, hiding Smartpay", [
                'quoteBillingCountry' => $quoteBillingCountry
            ]);
            return false;
        }
        $quoteShippingCountry = $quote->getShippingAddress()->getCountryId();
        if ($quoteShippingCountry && $quoteShippingCountry != 'JP') {
            $this->warning("[Smartpay] Filters/AllowCountries: \$quoteShippingCountry != JP, hiding Smartpay", [
                'quoteShippingCountry' => $quoteShippingCountry
            ]);
            return false;
        }

        return true;
    }
}
