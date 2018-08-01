<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Item\ValueLoader;

use Netgen\BlockManager\Item\ValueLoaderInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Throwable;

final class ProductValueLoader implements ValueLoaderInterface
{
    /**
     * @var \Sylius\Component\Product\Repository\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function load($id)
    {
        try {
            $product = $this->productRepository->find($id);
        } catch (Throwable $t) {
            return null;
        }

        return $product instanceof ProductInterface ? $product : null;
    }

    public function loadByRemoteId($remoteId)
    {
        return $this->load($remoteId);
    }
}
