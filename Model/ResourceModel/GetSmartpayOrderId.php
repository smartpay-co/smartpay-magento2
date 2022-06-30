<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class GetSmartpayOrderId
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * GetSmartpayOrderToken constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $entityId
     * @param string $modelEntity
     * @return string
     */
    public function execute(int $entityId, string $modelEntity): string
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()
            ->from($this->resourceConnection->getTableName($modelEntity), ['smartpay_order_id'])
            ->where('entity_id = ?', $entityId);

        return $connection->fetchOne($select);
    }
}
