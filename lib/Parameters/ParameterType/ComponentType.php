<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

final class ComponentType extends ParameterType implements ValueObjectProviderInterface
{
    public function __construct(private ValueObjectProviderInterface $valueObjectProvider) {}

    public static function getIdentifier(): string
    {
        return 'sylius_component';
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefault('allow_invalid', false);

        $optionsResolver->setAllowedTypes('allow_invalid', 'bool');
    }

    public function getValueObject($value): ?object
    {
        return $this->valueObjectProvider->getValueObject($value);
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, $value): array
    {
        $options = $parameterDefinition->getOptions();

        return [
            new Constraints\Type(['type' => 'string']),
            new SyliusConstraints\Component(
                [
                    'allowInvalid' => $options['allow_invalid'],
                ],
            ),
        ];
    }
}
