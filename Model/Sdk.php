<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Smartpay\Smartpay\Gateway\Settings;
use Smartpay\Smartpay;
use Smartpay\Api;

class Sdk
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var Api
     */
    private $api;

    /**
     * Capture constructor.
     * @param Settings $settings
     */
    public function __construct(
        Settings $settings
    ) {
        $this->settings = $settings;
        Smartpay::setApiUrl($this->settings->getApiUrl());
        $this->api = new Api($this->settings->getSecretKey(), $this->settings->getPublicKey());
    }

    /**
     * @return Api
     */
    public function getApi(): Api
    {
        return $this->api;
    }
}
