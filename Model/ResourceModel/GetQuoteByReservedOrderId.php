<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetQuoteByReservedOrderId
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * GetQuoteByReservedOrderId constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param string $reservedOrderId
     * @return int
     */
    public function execute(string $reservedOrderId): int
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from($this->resourceConnection->getTableName('quote'))
            ->where('reserved_order_id = ?', $reservedOrderId);

        return (int) $connection->fetchOne($select);
    }
}
