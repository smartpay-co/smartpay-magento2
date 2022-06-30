<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Controller\Cancel;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Index constructor.
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        RedirectFactory $redirectFactory
    ) {
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $redirect */
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath('checkout/cart', ['_secure' => true]);
    }
}
