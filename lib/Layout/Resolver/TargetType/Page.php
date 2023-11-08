<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Page extends TargetType
{
    /**
     * @param array<string, string> $availablePages
     */
    public function __construct(private readonly array $availablePages) {}

    public static function getType(): string
    {
        return 'sylius_page';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(['type' => 'string']),
            new SyliusConstraints\Page(),
        ];
    }

    public function provideValue(Request $request): ?string
    {
        $route = $request->attributes->get('_route');

        return $this->availablePages[$route] ?? null;
    }
}
