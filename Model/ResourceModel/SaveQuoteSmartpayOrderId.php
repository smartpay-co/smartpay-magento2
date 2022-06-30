<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class SaveQuoteSmartpayOrderId
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * SaveQuoteSmartpayOrderId constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $quoteId
     * @param string $smartpayOrderId
     * @return void
     */
    public function execute(int $quoteId, string $smartpayOrderId): void
    {
        $connection = $this->resourceConnection->getConnection();

        $connection->update(
            $this->resourceConnection->getTableName('quote'),
            ['smartpay_order_id' => $smartpayOrderId],
            'entity_id = ' . $quoteId
        );
    }
}
