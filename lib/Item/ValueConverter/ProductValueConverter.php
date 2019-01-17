<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueConverter;

use Netgen\BlockManager\Item\ValueConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class ProductValueConverter implements ValueConverterInterface
{
    public function supports($object): bool
    {
        return $object instanceof ProductInterface;
    }

    public function getValueType($object): string
    {
        return 'sylius_product';
    }

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return int|string
     */
    public function getId($object)
    {
        return $object->getId();
    }

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return int|string
     */
    public function getRemoteId($object)
    {
        return $object->getId();
    }

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     */
    public function getName($object): string
    {
        return (string) $object->getName();
    }

    public function getIsVisible($object): bool
    {
        return true;
    }

    public function getObject($object)
    {
        return $object;
    }
}
