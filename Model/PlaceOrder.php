<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Smartpay\Smartpay\Model\ResourceModel\SaveOrderSmartpayOrderId;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class PlaceOrder
{
    /**
     * @var CreateOrderAfterAuthorize
     */
    private $createOrderAfterAuthorize;
    /**
     * @var SaveOrderSmartpayOrderId
     */
    private $saveOrderSmartpayOrderId;
    /**
     * @var QuoteFactory
     */
    private $quoteFactory;
    /**
     * @var DoubleCheck
     */
    private $doubleCheck;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * PlaceOrder constructor.
     * @param CreateOrderAfterAuthorize $createOrderAfterAuthorize
     * @param SaveOrderSmartpayOrderId $saveOrderSmartpayOrderId
     * @param QuoteFactory $quoteFactory
     * @param DoubleCheck $doubleCheck
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        CreateOrderAfterAuthorize $createOrderAfterAuthorize,
        SaveOrderSmartpayOrderId  $saveOrderSmartpayOrderId,
        QuoteFactory              $quoteFactory,
        DoubleCheck               $doubleCheck,
        \Psr\Log\LoggerInterface  $logger
    ) {
        $this->createOrderAfterAuthorize = $createOrderAfterAuthorize;
        $this->saveOrderSmartpayOrderId = $saveOrderSmartpayOrderId;
        $this->quoteFactory = $quoteFactory;
        $this->doubleCheck = $doubleCheck;
        $this->logger = $logger;
    }

    /**
     * @param CheckoutSession $checkoutSession
     * @param int $quoteId
     * @return int
     * @throws LocalizedException
     * @noinspection PhpUndefinedMethodInspection
     */
    public function execute(
        CheckoutSession $checkoutSession,
        int $quoteId
    ): int {
        /** @noinspection PhpDeprecationInspection */
        $this->logger->info("[Smartpay] PlaceOrder: loading quote {$quoteId}");
        $quote = $this->quoteFactory->create()->load($quoteId);
        // Double check transaction validation
        if (!$this->doubleCheck->execute($quote)) {
            $this->logger->critical("[Smartpay] PlaceOrder: doubleCheck failed.");
            throw new AuthorizationException(__('Error on transaction validation (double check).'));
        }
        $checkoutSession->setPlaceOrder(true);
        /** @var OrderInterface|object|null $order */
        $this->logger->info("[Smartpay] PlaceOrder: creating order for quote {$quoteId}");
        $orderId = (int) $this->createOrderAfterAuthorize->execute($quote);
        $this->logger->info("[Smartpay] PlaceOrder: order {$orderId} created for quote {$quoteId}");
        $this->saveOrderSmartpayOrderId->execute($orderId, $quote->getSmartpayOrderId());
        $this->logger->info("[Smartpay] PlaceOrder: binding order {$orderId} <-> SmartpayOrderId {$quote->getSmartpayOrderId()}");
        $checkoutSession->unsPlaceOrder();

        return $orderId;
    }
}
