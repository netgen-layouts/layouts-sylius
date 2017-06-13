<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\TargetType;

use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\BlockManager\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class Product implements TargetTypeInterface
{
    /**
     * Returns the target type.
     *
     * @return string
     */
    public function getType()
    {
        return 'sylius_product';
    }

    /**
     * Returns the constraints that will be used to validate the target value.
     *
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getConstraints()
    {
        return array(
            new Constraints\NotBlank(),
            new Constraints\Type(array('type' => 'numeric')),
            new Constraints\GreaterThan(array('value' => 0)),
            new SyliusConstraints\Product(),
        );
    }

    /**
     * Provides the value for the target to be used in matching process.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function provideValue(Request $request)
    {
        $product = $request->attributes->get('ngbm_sylius_product');

        return $product instanceof ProductInterface ? $product->getId() : null;
    }
}