<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

final class ComponentType extends ParameterType implements ValueObjectProviderInterface
{
    public function __construct(
        private ComponentRepositoryInterface $componentRepository,
    ) {}

    public static function getIdentifier(): string
    {
        return 'sylius_component';
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver
            ->define('allow_invalid')
            ->required()
            ->default(false)
            ->allowedTypes('bool');
    }

    public function getValueObject(mixed $value): ?ComponentInterface
    {
        if ($value === null) {
            return null;
        }

        return $this->componentRepository->load(ComponentId::fromString((string) $value));
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\Type(type: 'string'),
            new SyliusConstraints\Component(
                allowInvalid: $parameterDefinition->getOption('allow_invalid'),
            ),
        ];
    }
}
