<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a channel in Sylius, if needed value object can also be retrieved.
 */
final class ChannelType extends ParameterType implements ValueObjectProviderInterface
{
    /**
     * @param \Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface> $channelRepository
     */
    public function __construct(private ChannelRepositoryInterface $channelRepository) {}

    public static function getIdentifier(): string
    {
        return 'sylius_channel';
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefault('multiple', false);
        $optionsResolver->setRequired(['multiple']);
        $optionsResolver->setAllowedTypes('multiple', 'bool');
    }

    public function getValueObject($value): ?ChannelInterface
    {
        return $this->channelRepository->find($value);
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\All(
                [
                    'constraints' => [
                        new Constraints\Type(['type' => 'numeric']),
                        new Constraints\GreaterThan(['value' => 0]),
                        new SyliusConstraints\Channel(),
                    ],
                ],
            ),
        ];
    }
}
