<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Block\Config\Source;

class ListOSMTheme implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'white', 'label' => __('White')],
            ['value' => 'dark', 'label' => __('Dark')],
            ['value' => 'light', 'label' => __('Light')]
        ];
    }
}
