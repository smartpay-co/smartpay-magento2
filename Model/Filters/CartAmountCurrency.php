<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\Filters;

use Magento\Quote\Api\Data\CartInterface;
use Smartpay\Smartpay\Gateway\Settings;

class CartAmountCurrency implements FilterInterface
{
    /**
     * @var Settings
     */
    private $settings;

    /**
     * CartAmountCurrency constructor.
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
        /**
         * Check if the amount is int
         * @param $x
         * @return bool
         */
        function isInt($x): bool
        {
            return (is_numeric($x) && (int)$x == $x);
        }

        if ($quote->getCurrency()->getQuoteCurrencyCode() != 'JPY') {
            return false;
        }
        if (!isInt($quote->getGrandTotal())) {
            return false;
        }

        foreach ($quote->getAllvisibleItems() as $item) {
            if (!isInt($item->getPriceInclTax())) {
                return false;
            }
        }

        return true;
    }
}
