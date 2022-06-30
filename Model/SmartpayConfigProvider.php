<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\Resolver;

/**
 * Smartpay config provider
 * @package Smartpay\Smartpay\Model
 */
class SmartpayConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Resolver
     */
    private $localeResolver;

    /**
     * @param Resolver $localeResolver
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Resolver $localeResolver,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver = $localeResolver;
    }

    /**
     * @return string
     */
    private function getLogo(): string
    {
        return '<span class="smartpay_checkout_logo"></span>';
    }

    /**
     * @inheirtDoc
     */
    public function getConfig(): array
    {
        return [
            'payment' => [
                'smartpay' => [
                    'logo' => $this->getLogo(),
                    'title' => __($this->scopeConfig->getValue('payment/smartpay/checkout_title')),
                    'instructions' => __($this->scopeConfig->getValue('payment/smartpay/instructions')),
                    'number_of_payments' => 3
                ]
            ]
        ];
    }
}
