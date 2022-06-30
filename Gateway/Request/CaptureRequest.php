<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

class CaptureRequest implements BuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(array $buildSubject): array
    {
        return [
            'payment' => $buildSubject['payment']->getPayment(),
            'amount' => $buildSubject['amount']
        ];
    }
}
