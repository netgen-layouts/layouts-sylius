<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetTypeInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Product implements TargetTypeInterface
{
    public static function getType(): string
    {
        return 'sylius_product';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(['type' => 'numeric']),
            new Constraints\GreaterThan(['value' => 0]),
            new SyliusConstraints\Product(),
        ];
    }

    public function provideValue(Request $request)
    {
        $product = $request->attributes->get('nglayouts_sylius_product');

        return $product instanceof ProductInterface ? $product->getId() : null;
    }
}
