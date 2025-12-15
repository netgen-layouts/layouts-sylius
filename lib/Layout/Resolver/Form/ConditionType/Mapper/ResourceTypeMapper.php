<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use function array_values;
use function str_replace;
use function ucfirst;

final class ResourceTypeMapper extends Mapper
{
    /**
     * @param array<string, string> $allowedResourceTypes
     */
    public function __construct(
        private array $allowedResourceTypes,
    ) {}

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => array_values($this->allowedResourceTypes),
            'choice_label' => $this->humanizeType(...),
            'choice_translation_domain' => false,
            'multiple' => true,
            'expanded' => true,
        ];
    }

    private function humanizeType(string $type): string
    {
        return ucfirst(str_replace(['-', '_'], ' ', $type));
    }
}
