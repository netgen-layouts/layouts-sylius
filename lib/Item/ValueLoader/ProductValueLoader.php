<?php

namespace Netgen\BlockManager\Sylius\Item\ValueLoader;

use Exception;
use Netgen\BlockManager\Exception\InvalidItemException;
use Netgen\BlockManager\Item\ValueLoaderInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

class ProductValueLoader implements ValueLoaderInterface
{
    /**
     * @var \Sylius\Component\Core\Repository\ProductRepositoryInterface
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
     * Loads the value from provided ID.
     *
     * @param int|string $id
     *
     * @throws \Netgen\BlockManager\Exception\InvalidItemException If value cannot be loaded
     *
     * @return mixed
     */
    public function load($id)
    {
        try {
            return $this->productRepository->find($id);
        } catch (Exception $e) {
            throw new InvalidItemException(
                sprintf('Product with ID %s could not be loaded.', $id),
                0,
                $e
            );
        }
    }
}
