<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Ui\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Smartpay\Smartpay\Gateway\Settings;

/**
 * Smartpay widget view model
 */
class OSMConfig implements ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var string
     */
    private $type;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Settings $settings
     * @param string $type
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Settings $settings,
        string $type
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->type = $type;
        $this->settings = $settings;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return (bool) $this->scopeConfig->getValue("payment/smartpay/osm/{$this->type}_visible");
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->scopeConfig->getValue('payment/smartpay/public_key');
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->scopeConfig->getValue('payment/smartpay/osm/theme');
    }

    /**
     * @return string
     */
    public function getLogoTheme(): string
    {
        return $this->scopeConfig->getValue('payment/smartpay/osm/logo_theme');
    }

    /**
     * @return string
     */
    public function getOSMClass(): string
    {
        return [
            'product' => 'smartpay-osm-product',
            'cart' => 'smartpay-osm-payment-method'
        ][$this->type];
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function isSmartpayActive(): bool
    {
        if (!$this->settings->isActive()) {
            return false;
        }

        return true;
    }
}
