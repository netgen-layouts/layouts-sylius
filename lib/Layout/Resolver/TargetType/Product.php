<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Product extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_product';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusConstraints\Product(),
        ];
    }

    public function provideValue(Request $request): ?int
    {
        $product = $request->attributes->get('nglayouts_sylius_resource');

        return $product instanceof ProductInterface ? $product->getId() : null;
    }

    public function getValueObject(mixed $value): ?ProductInterface
    {
        return $this->productRepository->find($value);
    }
}
