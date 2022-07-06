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
     * AllowCountries constructor.
     * @param Settings $settings
     */
    public function __construct(
        Settings $settings
    ) {
        $this->settings = $settings;
    }

    /**
     * @param CartInterface $quote
     * @return bool
     */
    public function execute(CartInterface $quote): bool
    {
        $quoteBillingCountry = $quote->getBillingAddress()->getCountryId();
        if ($quoteBillingCountry && $quoteBillingCountry != 'JP') {
            return false;
        }
        $quoteShippingCountry = $quote->getShippingAddress()->getCountryId();
        if ($quoteShippingCountry && $quoteShippingCountry != 'JP') {
            return false;
        }

        return true;
    }
}
