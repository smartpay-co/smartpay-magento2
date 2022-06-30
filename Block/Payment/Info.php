<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Block\Payment;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\ConfigurableInfo;
use Magento\Payment\Gateway\ConfigInterface;
use Smartpay\Smartpay\Model\GetPaymentInfo;

class Info extends ConfigurableInfo
{
    /**
     * @var GetPaymentInfo
     */
    private $getPaymentInfo;

    /**
     * Info constructor.
     * @param Context $context
     * @param ConfigInterface $config
     * @param GetPaymentInfo $getPaymentInfo
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigInterface $config,
        GetPaymentInfo $getPaymentInfo,
        array $data = []
    ) {
        $this->getPaymentInfo = $getPaymentInfo;
        parent::__construct($context, $config, $data);
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareSpecificInformation($transport = null): DataObject
    {
        $transport = parent::_prepareSpecificInformation($transport);
        $payment = $this->getInfo();
        $info = $this->getPaymentInfo->execute($payment);

        return $transport->addData($info);
    }
}
