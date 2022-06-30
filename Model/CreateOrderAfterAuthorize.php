<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteManagement;

class CreateOrderAfterAuthorize
{
    /**
     * @var QuoteManagement
     */
    private $quoteManagement;

    /**
     * CreateOrderAfterAuthorize constructor.
     * @param QuoteManagement $quoteManagement
     */
    public function __construct(
        QuoteManagement $quoteManagement
    ) {
        $this->quoteManagement = $quoteManagement;
    }

    /**
     * @param CartInterface $quote
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(CartInterface $quote): int
    {
        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
        return $this->quoteManagement->placeOrder($quote->getId(), $quote->getPayment());
    }
}
