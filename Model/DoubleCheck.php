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
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * DoubleCheck constructor.
     * @param GetOrderStatus $getOrderStatus
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GetOrderStatus           $getOrderStatus,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->getOrderStatus = $getOrderStatus;
        $this->logger = $logger;
    }

    /**
     * @param Quote $quote
     * @return bool
     * @noinspection PhpUndefinedMethodInspection
     */
    public function execute(Quote $quote): bool
    {
        if ($quote->getReservedOrderId() == '') {
            $this->logger->critical("[Smartpay] DoubleCheck: reservedOrderId not found for quote {$quote->getId()}");
            return false;
        }

        $orderStatus = $this->getOrderStatus->execute($quote->getSmartpayOrderId());
        if ($quote->getSmartpayOrderId() !== $orderStatus['id']
            || $quote->getGrandTotal() != $orderStatus['amount']
            || "order_" . $quote->getReservedOrderId() !== $orderStatus['reference']
            || $orderStatus['status'] !== 'requires_capture') {
            $this->logger->critical("[Smartpay] DoubleCheck: doubleCheck failed for quote {$quote->getId()}", [
                'smartpayOrderId' => "{$quote->getSmartpayOrderId()} !== {$orderStatus['id']}",
                'amount' => "{$quote->getGrandTotal()} != {$orderStatus['amount']}",
                'reservedOrderId' => "order_{$quote->getReservedOrderId()} !== {$orderStatus['reference']}",
                'smartpayOrderStatus' => "{$orderStatus['status']} !== 'requires_capture'"
            ]);
            return false;
        }

        return true;
    }
}
