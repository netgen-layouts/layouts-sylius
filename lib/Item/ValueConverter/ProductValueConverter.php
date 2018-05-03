<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Item\ValueConverter;

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

    public function getId($object)
    {
        return $object->getId();
    }

    public function getRemoteId($object)
    {
        return $object->getId();
    }

    public function getName($object): string
    {
        $name = $object->getName();

        return $name !== null ? $name : '';
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
