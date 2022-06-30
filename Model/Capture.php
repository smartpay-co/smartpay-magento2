<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Framework\Exception\AuthorizationException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Smartpay\Smartpay\Model\ResourceModel\GetSmartpayOrderId;

class Capture implements ClientInterface
{
    /**
     * @var GetSmartpayOrderId
     */
    private $getSmartpayOrderId;
    /**
     * @var Sdk
     */
    private $sdk;
    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * Capture constructor.
     * @param Sdk $sdk
     * @param GetSmartpayOrderId $getSmartpayOrderId
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(
        Sdk $sdk,
        GetSmartpayOrderId $getSmartpayOrderId,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->sdk = $sdk;
        $this->getSmartpayOrderId = $getSmartpayOrderId;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @inheritDoc
     * @throws AuthorizationException
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $transferObject->getBody()['payment']->getOrder();
        $smartpayOrderId = $this->getSmartpayOrderId->execute((int) $order->getEntityId(), 'sales_order');
        /** @noinspection PhpUndefinedMethodInspection */
        try {
            $response = $this->sdk->getApi()->capture([
                'order' => $smartpayOrderId,
                'amount' => $transferObject->getBody()['amount'],
                'currency' => $order->getOrderCurrencyCode(),
                'reference' => "order_".$order->getIncrementId(),
                'cancelRemainder' => 'manual',
            ]);
            $transferObject->getBody()['payment']->setTransactionId($response->asJson()['id']);
            // Close parent transaction(authorize) because we can't cancel a smartpay order after capture.
            // Disable this for now, we can't make void & multiple capture work together.
//            $authTransaction = $this->transactionRepository
//                ->getByTransactionType(TransactionInterface::TYPE_AUTH, $transferObject->getBody()['payment']->getId());
//            if ($authTransaction) {
//                $authTransaction->close();
//            }
        } catch (\Exception $e) {
            throw new AuthorizationException(__('Payment capture was not authorized from Smartpay.'));
        }

        return [];
    }
}

