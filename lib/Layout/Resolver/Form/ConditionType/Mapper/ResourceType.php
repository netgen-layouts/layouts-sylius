<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use function str_replace;
use function ucfirst;

final class ResourceType extends Mapper
{
    /**
     * @param array<string, string> $allowedResources
     */
    public function __construct(private readonly array $allowedResources) {}

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => $this->getResourceTypeList(),
            'choice_translation_domain' => false,
            'multiple' => true,
            'expanded' => true,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getResourceTypeList(): array
    {
        $resourceTypeList = [];

        foreach ($this->allowedResources as $type) {
            $resourceTypeList[$this->humanizeType($type)] = $type;
        }

        return $resourceTypeList;
    }

    private function humanizeType(string $type): string
    {
        return ucfirst(str_replace(['-', '_'], ' ', $type));
    }
}
