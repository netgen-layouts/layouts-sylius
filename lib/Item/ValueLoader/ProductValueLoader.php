<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Throwable;

final class ProductValueLoader implements ValueLoaderInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function load($id): ?ProductInterface
    {
        try {
            $product = $this->productRepository->find($id);
        } catch (Throwable) {
            return null;
        }

        return $product instanceof ProductInterface ? $product : null;
    }

    public function loadByRemoteId($remoteId): ?ProductInterface
    {
        return $this->load($remoteId);
    }
}
