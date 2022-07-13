<?php
declare(strict_types=1);

namespace Smartpay\Smartpay\Ui\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Smartpay product page data view model
 */
class ProductPageData implements ArgumentInterface, DataViewModelInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        RequestInterface $request,
        ProductRepositoryInterface $productRepository
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    /**
     * @return int
     */
    private function getProductId(): int
    {
        if ($this->request->getParam('product_id')) {
            return (int) $this->request->getParam('product_id');
        } else {
            return (int) $this->request->getParam('id');
        }
    }

    /**
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getProduct(): ProductInterface
    {
        return $this->productRepository->getById($this->getProductId());
    }

    /**
     * @return float
     * @throws NoSuchEntityException
     */
    public function getPrice(): float
    {
        $product = $this->getProduct();
        return (float) $product->getFinalPrice();
    }
}
