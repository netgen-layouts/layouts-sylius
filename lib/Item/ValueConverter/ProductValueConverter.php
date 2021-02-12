<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueConverter;

use Netgen\Layouts\Item\ValueConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueConverterInterface<\Sylius\Component\Product\Model\ProductInterface>
 */
final class ProductValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof ProductInterface;
    }

    public function getValueType(object $object): string
    {
        return 'sylius_product';
    }

    public function getId(object $object): int
    {
        return $object->getId();
    }

    public function getRemoteId(object $object): int
    {
        return $object->getId();
    }

    public function getName(object $object): string
    {
        return (string) $object->getName();
    }

    public function getIsVisible(object $object): bool
    {
        return true;
    }

    public function getObject(object $object): ProductInterface
    {
        return $object;
    }
}
