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
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * CartAmountCurrency constructor.
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
            $this->logger->warning("[Smartpay] Filters/CartAmountCurrency: \$quote->getCurrency()->getQuoteCurrencyCode() != 'JPY', hiding Smartpay", [
                'currencyCode' => $quote->getCurrency()->getQuoteCurrencyCode()
            ]);
            return false;
        }
        if (!isInt($quote->getGrandTotal())) {
            $this->logger->warning("[Smartpay] Filters/CartAmountCurrency: \$quote->geGrandTotal() is not int, hiding Smartpay", [
                'grandTotal' => $quote->getGrandTotal()
            ]);
            return false;
        }

        foreach ($quote->getAllvisibleItems() as $item) {
            if (!isInt($item->getPriceInclTax())) {
                $this->logger->warning("[Smartpay] Filters/CartAmountCurrency: \$item->getPriceInclTax() is not int, hiding Smartpay", [
                    'itemName' => $item->getName(),
                    'itemId' => $item->getEntityId(),
                    'getPriceInclTax' => $item->getPriceInclTax()
                ]);
                return false;
            }
        }

        return true;
    }
}
