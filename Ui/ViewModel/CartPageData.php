<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Ui\ViewModel;

use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;

/**
 * Smartpay cart/checkout page data view model
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class CartPageData implements ArgumentInterface, DataViewModelInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return Quote
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getQuote(): Quote
    {
        return $this->checkoutSession->getQuote();
    }

    /**
     * @return float
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getPrice(): float
    {
        return (float) $this->getQuote()->getGrandTotal();
    }
}
