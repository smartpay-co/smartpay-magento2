<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Smartpay\Smartpay\Model\ResourceModel\GetSmartpayOrderId;

class CreateAuthTransaction
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var GetSmartpayOrderId
     */
    private $getSmartpayOrderId;

    /**
     * CreateAuthTransaction constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param GetSmartpayOrderId $getSmartpayOrderId
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        getSmartpayOrderId $getSmartpayOrderId
    ) {
        $this->orderRepository = $orderRepository;
        $this->getSmartpayOrderId = $getSmartpayOrderId;
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function execute(int $orderId)
    {
        // Create authorize transaction, so we can void in the future.
        $order = $this->orderRepository->get($orderId);
        $smartpayOrderId = $this->getSmartpayOrderId->execute((int) $order->getEntityId(), 'sales_order');
        $payment = $order->getPayment();
        $payment->setIsTransactionClosed(false)->setTransactionId($smartpayOrderId);
        $transaction = $payment->addTransaction(TransactionInterface::TYPE_AUTH, null, true);

        $formattedPrice = $order->getBaseCurrency()->formatTxt($order->getGrandTotal());
        $payment->addTransactionCommentsToOrder($transaction, 'The authorized amount is ' . $formattedPrice);
        $payment->setAmountAuthorized($order->getGrandTotal());
        $payment->setBaseAmountAuthorized($order->getBaseGrandTotal());
        $this->orderRepository->save($order);
    }
}
