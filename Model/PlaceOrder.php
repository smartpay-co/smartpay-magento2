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
     * PlaceOrder constructor.
     * @param CreateOrderAfterAuthorize $createOrderAfterAuthorize
     * @param SaveOrderSmartpayOrderId $saveOrderSmartpayOrderId
     * @param QuoteFactory $quoteFactory
     * @param DoubleCheck $doubleCheck
     */
    public function __construct(
        CreateOrderAfterAuthorize $createOrderAfterAuthorize,
        SaveOrderSmartpayOrderId  $saveOrderSmartpayOrderId,
        QuoteFactory              $quoteFactory,
        DoubleCheck               $doubleCheck
    ) {
        $this->createOrderAfterAuthorize = $createOrderAfterAuthorize;
        $this->saveOrderSmartpayOrderId = $saveOrderSmartpayOrderId;
        $this->quoteFactory = $quoteFactory;
        $this->doubleCheck = $doubleCheck;
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
        $quote = $this->quoteFactory->create()->load($quoteId);
        // Double check transaction validation
        if (!$this->doubleCheck->execute($quote)) {
            throw new AuthorizationException(__('Error on transaction validation (double check).'));
        }
        $checkoutSession->setPlaceOrder(true);
        /** @var OrderInterface|object|null $order */
        $orderId = (int) $this->createOrderAfterAuthorize->execute($quote);
        $this->saveOrderSmartpayOrderId->execute($orderId, $quote->getSmartpayOrderId());
        $checkoutSession->unsPlaceOrder();

        return $orderId;
    }
}
