<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Quote\Model\Quote;

class DoubleCheck
{
    /**
     * @var GetOrderStatus
     */
    private $getOrderStatus;

    /**
     * DoubleCheck constructor.
     * @param GetOrderStatus $getOrderStatus
     */
    public function __construct(
        GetOrderStatus $getOrderStatus
    ) {
        $this->getOrderStatus = $getOrderStatus;
    }

    /**
     * @param Quote $quote
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    public function execute(Quote $quote): bool
    {
        if ($quote->getReservedOrderId() == '') return false;

        $orderStatus = $this->getOrderStatus->execute($quote->getSmartpayOrderId());
        if ($quote->getSmartpayOrderId() !== $orderStatus['id']
            || $quote->getGrandTotal() != $orderStatus['amount']
            || "order_".$quote->getReservedOrderId() !== $orderStatus['reference']
            || $orderStatus['status'] !== 'requires_capture') {
            return false;
        }

        return true;
    }
}
