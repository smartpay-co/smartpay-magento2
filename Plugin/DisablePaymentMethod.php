<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Plugin;

use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Model\MethodList;
use Magento\Quote\Api\Data\CartInterface;
use Smartpay\Smartpay\Gateway\Settings;
use Smartpay\Smartpay\Model\ProcessFilters;

class DisablePaymentMethod
{
    /**
     * @var ProcessFilters
     */
    private $processFilters;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * DisablePaymentMethod constructor.
     * @param ProcessFilters $processFilters
     * @param Settings $settings
     */
    public function __construct(
        ProcessFilters $processFilters,
        Settings $settings
    ) {
        $this->processFilters = $processFilters;
        $this->settings = $settings;
    }

    /**
     * @param MethodList $subject
     * @param array $availableMethods
     * @param CartInterface|null $quote
     * @return MethodInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAvailableMethods(
        MethodList $subject,
        array $availableMethods,
        CartInterface $quote = null
    ): array {
        if (!$this->processFilters->execute($quote)) {
            /** @var \Magento\Payment\Api\Data\PaymentMethodInterface $paymentMethod */
            foreach ($availableMethods as $key =>$paymentMethod) {
                if ($paymentMethod->getCode() === $this->settings->getCode()) {
                    unset($availableMethods[$key]);
                    $availableMethods = array_values($availableMethods);
                }
            }
        }

        return $availableMethods;
    }
}
