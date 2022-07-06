<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use InvalidArgumentException;
use Magento\Quote\Api\Data\CartInterface;
use Smartpay\Smartpay\Model\Filters\FilterInterface;

class ProcessFilters
{
    /**
     * @var array
     */
    private $filters;

    /**
     * ProcessFilters constructor.
     * @param array $filters
     */
    public function __construct(
        array $filters = []
    ) {
        $this->filters = $filters;

        foreach ($this->filters as $filterCode => $filter) {
            if (!($filter instanceof FilterInterface)) {
                throw new InvalidArgumentException(
                    'Filter ' . $filterCode . ' must implement ' . FilterInterface::class
                );
            }
        }
    }

    /**
     * @param CartInterface $quote
     * @return bool
     */
    public function execute(CartInterface $quote): bool
    {
        /** @var FilterInterface $filter */
        foreach ($this->filters as $filter) {
            if (!$filter->execute($quote)) {
                return false;
            }
        }

        return true;
    }
}
