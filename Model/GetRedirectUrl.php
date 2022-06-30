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
     * @param Sdk $sdk
     * @param UrlInterface $url
     * @param GenerateCheckoutSessionRequestPayload $generateCheckoutSessionRequestPayload
     * @param ManagerInterface $messageManager
     * @param SaveQuoteSmartpayOrderId $saveQuoteSmartpayOrderId
     */
    public function __construct(
        Sdk $sdk,
        UrlInterface $url,
        GenerateCheckoutSessionRequestPayload $generateCheckoutSessionRequestPayload,
        ManagerInterface $messageManager,
        SaveQuoteSmartpayOrderId $saveQuoteSmartpayOrderId
    ) {
        $this->sdk = $sdk;
        $this->generateCheckoutSessionRequestPayload = $generateCheckoutSessionRequestPayload;
        $this->messageManager = $messageManager;
        $this->url = $url;
        $this->saveQuoteSmartpayOrderId = $saveQuoteSmartpayOrderId;
    }

    /**
     * @param CartInterface $quote
     * @return string
     * @throws NoSuchEntityException
     */
    public function execute(CartInterface $quote): string
    {
        $checkoutSessionRequestPayload = $this->generateCheckoutSessionRequestPayload->execute($quote);

        try {
            $checkoutSession = $this->sdk->getApi()->checkoutSession($checkoutSessionRequestPayload);
            $checkoutSessionJson = $checkoutSession->asJson();
            if ($quote->getId() && $checkoutSessionJson['order']) {
                $this->saveQuoteSmartpayOrderId->execute((int)$quote->getId(), $checkoutSessionJson['order']['id']);
            }
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            return $this->url->getUrl('checkout/cart');
        }

        return $checkoutSession->redirectUrl();
    }
}
