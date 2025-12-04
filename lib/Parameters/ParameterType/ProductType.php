<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a product in Sylius, if needed value object can also be retrieved.
 */
final class ProductType extends ParameterType implements ValueObjectProviderInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public static function getIdentifier(): string
    {
        return 'sylius_product';
    }

    public function getValueObject(mixed $value): ?ProductInterface
    {
        return $this->productRepository->find($value);
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusConstraints\Product(),
        ];
    }
}
