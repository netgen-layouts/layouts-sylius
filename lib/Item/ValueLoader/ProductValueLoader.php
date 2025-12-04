<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Throwable;

final class ProductValueLoader implements ValueLoaderInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function load(int|string $id): ?ProductInterface
    {
        try {
            return $this->productRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?ProductInterface
    {
        return $this->load($remoteId);
    }
}
