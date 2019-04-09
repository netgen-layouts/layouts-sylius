<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueConverter;

use Netgen\BlockManager\Item\ValueConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;

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

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return int|string
     */
    public function getId(object $object)
    {
        return $object->getId();
    }

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return int|string
     */
    public function getRemoteId(object $object)
    {
        return $object->getId();
    }

    /**
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     */
    public function getName(object $object): string
    {
        return (string) $object->getName();
    }

    public function getIsVisible(object $object): bool
    {
        return true;
    }

    public function getObject(object $object): object
    {
        return $object;
    }
}
