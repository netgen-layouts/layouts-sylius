<?php

namespace Netgen\BlockManager\Sylius\Item\ValueConverter;

use Netgen\BlockManager\Item\ValueConverterInterface;
use Sylius\Component\Product\Model\ProductInterface;

class ProductValueConverter implements ValueConverterInterface
{
    /**
     * Returns if the converter supports the object.
     *
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return bool
     */
    public function supports($object)
    {
        return $object instanceof ProductInterface;
    }

    /**
     * Returns the value type for this object.
     *
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return string
     */
    public function getValueType($object)
    {
        return 'sylius_product';
    }

    /**
     * Returns the object ID.
     *
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return int|string
     */
    public function getId($object)
    {
        return $object->getId();
    }

    /**
     * Returns the object name.
     *
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return string
     */
    public function getName($object)
    {
        return $object->getName();
    }

    /**
     * Returns if the object is visible.
     *
     * @param \Sylius\Component\Product\Model\ProductInterface $object
     *
     * @return bool
     */
    public function getIsVisible($object)
    {
        return true;
    }
}
