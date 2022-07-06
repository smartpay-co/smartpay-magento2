<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Block\Adminhtml\Order\Creditmemo\Create;

use Smartpay\Smartpay\Gateway\Settings;

class Items extends \Magento\Sales\Block\Adminhtml\Order\Creditmemo\Create\Items
{
    /**
     * Override _prepareLayout to hide Refund Offline button
     * @return $this|Items
     */
    protected function _prepareLayout(): Items
    {
        parent::_prepareLayout();
        if ($this->getOrder()->getPayment()->getMethod() == Settings::CODE) {
            $this->unsetChild('submit_offline');
        }
        return $this;
    }
}
