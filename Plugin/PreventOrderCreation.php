<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\QuoteManagement;
use Smartpay\Smartpay\Gateway\Settings;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class PreventOrderCreation
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * PreventOrderCreation constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param CheckoutSession $checkoutSession
     * @param Settings $settings
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        CartRepositoryInterface  $cartRepository,
        CheckoutSession          $checkoutSession,
        Settings                 $settings,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->cartRepository = $cartRepository;
        $this->checkoutSession = $checkoutSession;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    /**
     * @param QuoteManagement $subject
     * @param callable $proceed
     * @param $cartId
     * @param PaymentInterface|null $paymentMethod
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUndefinedMethodInspection
     */
    public function aroundPlaceOrder(
        QuoteManagement  $subject,
        callable         $proceed,
                         $cartId,
        PaymentInterface $paymentMethod = null
    ): int
    {
        $quote = $this->cartRepository->get($cartId);
        if ($quote->getPayment()->getMethod() === $this->settings->getCode()
            && !$this->checkoutSession->getPlaceOrder()) {
            $quote->reserveOrderId();
            $this->cartRepository->save($quote);
            $this->checkoutSession->setReservedOrderId($quote->getReservedOrderId());

            $this->logger->debug("[Smartpay] PreventOrderCreation: quoteId {$quote->getId()} reservedOrderId {$quote->getReservedOrderId()}");

            return 0;
        }

        return (int)$proceed($cartId, $paymentMethod);
    }
}
