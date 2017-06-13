<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

class SyliusRuntime
{
    /**
     * @var \Sylius\Component\Product\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Constructor.
     *
     * @param \Sylius\Component\Product\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Returns the product name.
     *
     * @param int|string $productId
     *
     * @return string
     */
    public function getProductName($productId)
    {
        $product = $this->productRepository->find($productId);
        if (!$product instanceof ProductInterface) {
            return null;
        }

        return $product->getName();
    }
}
