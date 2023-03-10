<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\ConditionType;

use Netgen\Layouts\Layout\Resolver\ConditionType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function in_array;

final class Locale extends ConditionType
{
    public static function getType(): string
    {
        return 'sylius_locale';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\All(
                [
                    'constraints' => [
                        new Constraints\Type(['type' => 'string']),
                        new SyliusConstraints\Locale(),
                    ],
                ],
            ),
        ];
    }

    public function matches(Request $request, mixed $value): bool
    {
        $locale = $request->getLocale();

        return in_array($locale, $value, true);
    }
}
