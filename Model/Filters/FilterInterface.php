<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\Filters;

use Magento\Quote\Api\Data\CartInterface;

interface FilterInterface
{
    /**
     * @param CartInterface $quote
     * @return bool
     */
    public function execute(CartInterface $quote): bool;
}
