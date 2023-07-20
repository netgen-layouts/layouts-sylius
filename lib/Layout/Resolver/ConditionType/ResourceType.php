<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\ConditionType;

use Netgen\Layouts\Layout\Resolver\ConditionType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_filter;
use function array_flip;
use function array_map;
use function is_a;

final class ResourceType extends ConditionType
{
    /**
     * @param array<string, string> $allowedResources
     */
    public function __construct(private readonly array $allowedResources)
    {
    }

    public static function getType(): string
    {
        return 'sylius_resource_type';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\All(
                [
                    'constraints' => [
                        new Constraints\Type(['type' => 'string']),
                        new SyliusConstraints\ResourceType(),
                    ],
                ],
            ),
        ];
    }

    public function matches(Request $request, mixed $value): bool
    {
        $resource = $request->attributes->get('nglayouts_sylius_resource');

        $allowedClasses = array_filter(
            array_map(fn (string $type): ?string => array_flip($this->allowedResources)[$type] ?? null, $value),
        );

        /** @var class-string $allowedClass */
        foreach ($allowedClasses as $allowedClass) {
            if (is_a($resource, $allowedClass)) {
                return true;
            }
        }

        return false;
    }
}
