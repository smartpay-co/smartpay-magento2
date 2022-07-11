<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Helper\Image;

class GenerateCheckoutSessionRequestPayload
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * GetOrderDetails constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Image $imageHelper
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param CartInterface $quote
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(CartInterface $quote): array
    {
        $payload = [];
        $payload['captureMethod'] = 'manual';
        $payload['amount'] = $quote->getGrandTotal();
        $payload['currency'] = $quote->getQuoteCurrencyCode();
        $payload['reference'] = "order_".$quote->getReservedOrderId();
        $payload['successUrl'] = $this->storeManager->getStore()->getUrl('smartpay/confirm') . "?quoteId=" . $quote->getId();
        $payload['cancelUrl'] = $this->storeManager->getStore()->getUrl('smartpay/cancel');
        $payload['items'] = [];
        foreach ($quote->getAllvisibleItems() as $quoteItem) {
            $item = [];
            $item['name'] = $quoteItem->getName();
            $item['quantity'] = $quoteItem->getQty();
            $item['currency'] = $quote->getQuoteCurrencyCode();
            $item['amount'] = $quoteItem->getPriceInclTax();
            $item['url']  =$quoteItem->getProduct()->getProductUrl();
            $item['images'] = [$this->imageHelper->init($quoteItem->getProduct(), 'product_thumbnail_image')->getUrl()];
            $payload['items'][] = $item;
        }
        $payload['customerInfo'] = [];
        $payload['customerInfo']['email'] = $quote->getBillingAddress()->getEmail();
        $payload['customerInfo']['firstName'] = $quote->getBillingAddress()->getFirstname();
        $payload['customerInfo']['lastName'] = $quote->getBillingAddress()->getLastname();
        $payload['customerInfo']['address'] = [
            'line1' => $quote->getBillingAddress()->getStreetLine(1),
            'line2' => $quote->getBillingAddress()->getStreetLine(2),
            'administrativeArea' => $quote->getBillingAddress()->getRegion(),
            'locality' => $quote->getBillingAddress()->getCity(),
            'postalCode' => $quote->getBillingAddress()->getPostcode(),
            'country' => $quote->getBillingAddress()->getCountryId(),
        ];
        $payload['shippingInfo'] = [];
        $payload['shippingInfo']['address'] = [
            'line1' => $quote->getShippingAddress()->getStreetLine(1),
            'line2' => $quote->getShippingAddress()->getStreetLine(2),
            'administrativeArea' => $quote->getShippingAddress()->getRegion(),
            'locality' => $quote->getShippingAddress()->getCity(),
            'postalCode' => $quote->getShippingAddress()->getPostcode(),
            'country' => $quote->getShippingAddress()->getCountryId(),
        ];
        $payload['shippingInfo']['feeAmount'] = $quote->getShippingAddress()->getShippingAmount();
        $payload['shippingInfo']['feeCurrency'] = $quote->getQuoteCurrencyCode();

        return $payload;
    }
}
