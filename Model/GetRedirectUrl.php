<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Quote\Api\Data\CartInterface;
use Smartpay\Smartpay\Model\ResourceModel\SaveQuoteSmartpayOrderId;

class GetRedirectUrl
{

    /**
     * @var Sdk
     */
    private $sdk;
    /**
     * @var GenerateCheckoutSessionRequestPayload
     */
    private $generateCheckoutSessionRequestPayload;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var SaveQuoteSmartpayOrderId
     */
    private $saveQuoteSmartpayOrderId;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param Sdk $sdk
     * @param UrlInterface $url
     * @param GenerateCheckoutSessionRequestPayload $generateCheckoutSessionRequestPayload
     * @param ManagerInterface $messageManager
     * @param SaveQuoteSmartpayOrderId $saveQuoteSmartpayOrderId
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Sdk                                   $sdk,
        UrlInterface                          $url,
        GenerateCheckoutSessionRequestPayload $generateCheckoutSessionRequestPayload,
        ManagerInterface                      $messageManager,
        SaveQuoteSmartpayOrderId              $saveQuoteSmartpayOrderId,
        \Psr\Log\LoggerInterface              $logger
    )
    {
        $this->sdk = $sdk;
        $this->generateCheckoutSessionRequestPayload = $generateCheckoutSessionRequestPayload;
        $this->messageManager = $messageManager;
        $this->url = $url;
        $this->saveQuoteSmartpayOrderId = $saveQuoteSmartpayOrderId;
        $this->logger = $logger;
    }

    /**
     * @param CartInterface $quote
     * @return string
     * @throws NoSuchEntityException
     */
    public function execute(CartInterface $quote): string
    {
        $checkoutSessionRequestPayload = $this->generateCheckoutSessionRequestPayload->execute($quote);
        $this->logger->debug("[Smartpay] GetRedirectUrl: checkoutSessionRequestPayload", ['payload' => $checkoutSessionRequestPayload]);
        try {
            $checkoutSession = $this->sdk->getApi()->checkoutSession($checkoutSessionRequestPayload);
            $checkoutSessionJson = $checkoutSession->asJson();
            if ($quote->getId() && $checkoutSessionJson['order']) {
                $this->saveQuoteSmartpayOrderId->execute((int)$quote->getId(), $checkoutSessionJson['order']['id']);
                $this->logger->info("[Smartpay] GetRedirectUrl: binding quote {$quote->getId()} <-> smartpayOrderId {$checkoutSessionJson['order']['id']}");
            }
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $this->logger->critical("[Smartpay] GetRedirectUrl: error", ['exception' => $e]);
            if ($e instanceof \GuzzleHttp\Exception\BadResponseException) {
                $this->logger->critical(
                    "[Smartpay] GetRedirectUrl: error response",
                    ['response' => $e->getResponse()->getBody()]
                );
            }
            return $this->url->getUrl('checkout/cart');
        }

        return $checkoutSession->redirectUrl();
    }
}
