<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class SaveOrderSmartpayOrderId
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * SaveOrderSmartpayOrderId constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $orderId
     * @param string $smartpayOrderId
     * @return void
     */
    public function execute(int $orderId, string $smartpayOrderId): void
    {
        $connection = $this->resourceConnection->getConnection();

        $connection->update(
            $this->resourceConnection->getTableName('sales_order'),
            ['smartpay_order_id' => $smartpayOrderId],
            'entity_id = ' . $orderId
        );
    }
}
