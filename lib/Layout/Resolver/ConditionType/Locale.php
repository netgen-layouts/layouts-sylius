<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\ConditionType;

use Netgen\Layouts\Layout\Resolver\ConditionType;
use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use function count;
use function in_array;

final class Locale extends ConditionType
{
    private LocaleProviderInterface $localeProvider;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

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

    public function matches(Request $request, $value): bool
    {
        $locales = $this->localeProvider->getRequestLocales($request);

        if (count($locales) > 0) {
            return in_array($locales[0], $value, true);
        }

        return false;
    }
}
