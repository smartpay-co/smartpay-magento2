<?php

declare(strict_types=1);

namespace Smartpay\Smartpay\Gateway;

use Magento\Payment\Gateway\Config\Config as GatewayConfig;

/**
 * Gateway config
 */
class Settings extends GatewayConfig
{
    const CODE = 'smartpay';
    const ACTIVE = 'active';
    const TITLE = 'title';
    const API_URL = 'api_url';
    const PUBLIC_KEY = 'public_key';
    const SECRET_KEY = 'secret_key';
    const MANUAL_CAPTURE = 'manual_capture';
    const INSTRUCTIONS = 'instructions';

    /**
     * @return string
     */
    public function getCode(): string
    {
        return self::CODE;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getValue(self::ACTIVE);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getValue(self::TITLE);
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->getValue(self::API_URL);
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->getValue(self::PUBLIC_KEY);
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->getValue(self::SECRET_KEY);
    }

    /**
     * @return bool
     */
    public function getManualCapture(): bool
    {
        return (bool) $this->getValue(self::MANUAL_CAPTURE);
    }

    /**
     * @return string
     */
    public function getInstructions(): string
    {
        return $this->getValue(self::INSTRUCTIONS);
    }
}
