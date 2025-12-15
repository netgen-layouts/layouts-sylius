<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a channel in Sylius, if needed value object can also be retrieved.
 */
final class ChannelType extends ParameterType
{
    public static function getIdentifier(): string
    {
        return 'sylius_channel';
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver
            ->define('multiple')
            ->required()
            ->default(false)
            ->allowedTypes('bool');
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\All(
                constraints: [
                    new Constraints\Type(type: 'int'),
                    new Constraints\Positive(),
                    new SyliusConstraints\Channel(),
                ],
            ),
        ];
    }
}
