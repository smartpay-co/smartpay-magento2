<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Block\Config\Source;

class ListOSMLogoTheme implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'color', 'label' => __('Color')],
            ['value' => 'solid', 'label' => __('Solid')],
            ['value' => 'line', 'label' => __('Line')]
        ];
    }
}
