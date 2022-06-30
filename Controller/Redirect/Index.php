<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Controller\Redirect;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Smartpay\Smartpay\Model\GetRedirectUrl;

class Index implements HttpGetActionInterface
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var GetRedirectUrl
     */
    private $getRedirectUrl;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Index constructor.
     * @param RedirectFactory $redirectFactory
     * @param CheckoutSession $checkoutSession
     * @param GetRedirectUrl $getRedirectUrl
     * @param CartRepositoryInterface $cartRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        CheckoutSession $checkoutSession,
        GetRedirectUrl $getRedirectUrl,
        CartRepositoryInterface $cartRepository,
        ManagerInterface $messageManager
    ) {
        $this->redirectFactory = $redirectFactory;
        $this->checkoutSession = $checkoutSession;
        $this->getRedirectUrl = $getRedirectUrl;
        $this->cartRepository = $cartRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $redirect */
        $redirect = $this->redirectFactory->create();
        try {
            if ($this->checkoutSession->getQuoteId() === null) {
                if (isset($_SESSION['default']['visitor_data']['quote_id'])) {
                    $this->checkoutSession->setQuoteId($_SESSION['default']['visitor_data']['quote_id']);
                }
            }
            $quote = $this->cartRepository->get((int) $this->checkoutSession->getQuoteId());
            return $redirect->setUrl($this->getRedirectUrl->execute($quote));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            return $redirect->setPath('checkout/cart', ['_secure' => true]);
        }
    }
}
