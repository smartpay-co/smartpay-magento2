<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Block\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Disabled extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $element->setDisabled('disabled');
        $element->setReadonly(true);

        return $element->getElementHtml();
    }
}
