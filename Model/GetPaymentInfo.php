<?php

declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Payment\Model\InfoInterface;

class GetPaymentInfo
{
    /**
     * @param InfoInterface $payment
     * @return array
     */
    public function execute(InfoInterface $payment): array
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return [
            'Smartpay Order ID' => $payment->getOrder()->getSmartpayOrderId() ?? "None"
        ];
    }
}
