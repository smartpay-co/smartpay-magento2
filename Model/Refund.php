<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Model;

use Magento\Framework\Message\ManagerInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Smartpay\Smartpay;

class Refund implements ClientInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var Sdk
     */
    private $sdk;

    /**
     * Refund constructor.
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ManagerInterface $messageManager,
        Sdk $sdk
    ) {
        $this->messageManager = $messageManager;
        $this->sdk = $sdk;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $creditMemo = $transferObject->getBody()['payment']->getCreditmemo();
        $smartpayPaymentId = $creditMemo->getInvoice()->getTransactionId();
        if ($smartpayPaymentId === null) {
            throw new \Exception(__('Missing Smartpay Order Id'));
        }

        try {
            $response = $this->sdk->getApi()->refund([
                'payment' => $smartpayPaymentId,
                'amount' => $creditMemo->getGrandTotal(),
                'currency' => $creditMemo->getOrderCurrencyCode(),
                'reference' => "creditmemo_".$creditMemo->getIncrementId(),
                'reason' => Smartpay::REJECT_REQUEST_BY_CUSTOMER,
            ]);
            $transferObject->getBody()['payment']->setTransactionId($response->asJson()['id']);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return [];
    }
}
