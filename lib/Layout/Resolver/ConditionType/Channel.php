<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\ConditionType;

use Netgen\Layouts\Layout\Resolver\ConditionType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function count;
use function in_array;
use function is_array;

final class Channel extends ConditionType
{
    public function __construct(private ChannelContextInterface $channelContext)
    {
    }

    public static function getType(): string
    {
        return 'sylius_channel';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
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

    public function matches(Request $request, mixed $value): bool
    {
        try {
            $channel = $this->channelContext->getChannel();
        } catch (ChannelNotFoundException) {
            return false;
        }

        if (!is_array($value) || count($value) === 0) {
            return false;
        }

        return in_array($channel->getId(), $value, true);
    }
}
