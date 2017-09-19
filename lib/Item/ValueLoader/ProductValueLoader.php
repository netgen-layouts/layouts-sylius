<?php

namespace Netgen\BlockManager\Sylius\Item\ValueLoader;

use Exception;
use Netgen\BlockManager\Exception\Item\ItemException;
use Netgen\BlockManager\Item\ValueLoaderInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

class ProductValueLoader implements ValueLoaderInterface
{
    /**
     * @var \Sylius\Component\Core\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function load($id)
    {
        try {
            $product = $this->productRepository->find($id);
        } catch (Exception $e) {
            throw ItemException::noValue($id);
        }

        if (!$product instanceof ProductInterface) {
            throw ItemException::noValue($id);
        }

        return $product;
    }
}
