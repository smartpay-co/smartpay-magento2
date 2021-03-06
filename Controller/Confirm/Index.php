<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Controller\Confirm;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\InvoiceOrderInterface;
use Magento\Framework\App\RequestInterface;
use Smartpay\Smartpay\Gateway\Settings;
use Smartpay\Smartpay\Model\PlaceOrder;
use Smartpay\Smartpay\Model\CreateAuthTransaction;
use Smartpay\Smartpay\Model\ResourceModel\GetQuoteByReservedOrderId;

class Index implements HttpGetActionInterface
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var InvoiceOrderInterface
     */
    private $invoiceOrder;
    /**
     * @var Session
     */
    private $checkoutSession;
    /**
     * @var GetQuoteByReservedOrderId
     */
    private $getQuoteByReservedOrderId;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var PlaceOrder
     */
    private $placeOrder;
    /**
     * @var CreateAuthTransaction
     */
    private $createAuthTransaction;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Index constructor.
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param ManagerInterface $messageManager
     * @param InvoiceOrderInterface $invoiceOrder
     * @param Session $checkoutSession
     * @param GetQuoteByReservedOrderId $getQuoteByReservedOrderId
     * @param Settings $settings
     * @param PlaceOrder $placeOrder
     * @param CreateAuthTransaction $createAuthTransaction
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        RequestInterface $request,
        ManagerInterface $messageManager,
        InvoiceOrderInterface $invoiceOrder,
        Session $checkoutSession,
        GetQuoteByReservedOrderId $getQuoteByReservedOrderId,
        Settings $settings,
        PlaceOrder $placeOrder,
        CreateAuthTransaction $createAuthTransaction,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->invoiceOrder = $invoiceOrder;
        $this->checkoutSession = $checkoutSession;
        $this->getQuoteByReservedOrderId = $getQuoteByReservedOrderId;
        $this->settings = $settings;
        $this->placeOrder = $placeOrder;
        $this->createAuthTransaction = $createAuthTransaction;
        $this->logger = $logger;
    }

    /**
     * @return Redirect
     * @noinspection PhpUndefinedMethodInspection
     */
    public function execute(): Redirect
    {
        $status = true;
        $quoteId = null;

        if ($this->checkoutSession->getReservedOrderId()) {
            $quoteId = $this->getQuoteByReservedOrderId->execute($this->checkoutSession->getReservedOrderId());
            $this->logger->debug("[Smartpay] Confirm#Index: reservedOrderId found: {$quoteId}");
        }
        if ($quoteId == null) {
            $quoteId = (int)$this->request->getParam('quoteId');
            $this->logger->warning("[Smartpay] Confirm#Index: reservedOrderId not found, using redirected param quoteId {$quoteId} instead.");
        }
        $this->checkoutSession->unsPlaceOrder();
        try {
            $orderId = $this->placeOrder->execute($this->checkoutSession, $quoteId);
            $this->logger->info("[Smartpay] Confirm#Index: Order {$orderId} placed for quote {$quoteId}.");
            if ($this->settings->getManualCapture()) {
                $this->logger->debug("[Smartpay] Confirm#Index: ManualCapture mode, do nothing for order {$orderId}");
                // NOTE: With this we can void authorize tx, but we can't do multiple capture. Disable for now.
                // $this->createAuthTransaction->execute($orderId);
            } else {
                $this->logger->debug("[Smartpay] Confirm#Index: invoicing order {$orderId}");
                $this->invoiceOrder->execute($orderId, true);
            }
        } catch (LocalizedException | AuthorizationException $e) {
            $status = false;
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $this->logger->critical("[Smartpay] Confirm#Index: error", ['exception' => $e]);
        }

        if ($status) {
            $path = 'checkout/onepage/success';
        } else {
            $path = 'checkout/onepage/failure';
        }

        $this->checkoutSession->unsReservedOrderId();
        /** @var \Magento\Framework\Controller\Result\Redirect $redirect */
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath($path, ['_secure' => true]);
    }
}
