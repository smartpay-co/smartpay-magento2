<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Ui\ViewModel;

/**
 * Widget data view model interface
 * @spi
 */
interface DataViewModelInterface
{
    /**
     * @return float
     */
    public function getPrice(): float;
}
