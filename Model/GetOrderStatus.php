<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

class GetOrderStatus
{
    /**
     * @var Sdk
     */
    private $sdk;

    /**
     * GetPaymentStatus constructor.
     * @param Sdk $sdk
     */
    public function __construct(
        Sdk $sdk
    ) {
        $this->sdk = $sdk;
    }

    /**
     * @param string $smartpayOrderId
     * @return array
     */
    public function execute(string $smartpayOrderId): array
    {
        return $this->sdk->getApi()->getOrder([ 'id' => $smartpayOrderId ])->asJson();
    }
}
