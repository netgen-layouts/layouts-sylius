<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\ConditionType;

use Netgen\Layouts\Layout\Resolver\ConditionType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_any;
use function array_filter;
use function array_flip;
use function array_map;

final class ResourceType extends ConditionType
{
    /**
     * @param array<string, string> $allowedResources
     */
    public function __construct(
        private array $allowedResources,
    ) {}

    public static function getType(): string
    {
        return 'sylius_resource_type';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\All(
                constraints: [
                    new Constraints\Type(type: 'string'),
                    new SyliusConstraints\ResourceType(),
                ],
            ),
        ];
    }

    public function matches(Request $request, int|string|array $value): bool
    {
        $resource = $request->attributes->get('nglayouts_sylius_resource');

        $allowedClasses = array_filter(
            array_map(fn (string $type): ?string => array_flip($this->allowedResources)[$type] ?? null, (array) $value),
            static fn (?string $value): bool => $value !== null,
        );

        return array_any(
            $allowedClasses,
            static fn (string $allowedClass): bool => $resource instanceof $allowedClass,
        );
    }
}
