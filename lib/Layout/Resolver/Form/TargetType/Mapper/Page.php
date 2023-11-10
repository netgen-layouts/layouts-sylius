<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\TargetType\Mapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use function array_values;
use function str_replace;
use function ucfirst;

final class Page extends Mapper
{
    /**
     * @param array<string, string> $allowedPages
     */
    public function __construct(private array $allowedPages) {}

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => array_values($this->allowedPages),
            'choice_label' => fn (string $type): string => $this->humanizePage($type),
            'choice_translation_domain' => false,
            'multiple' => false,
            'expanded' => false,
        ];
    }

    private function humanizePage(string $allowedPages): string
    {
        return ucfirst(str_replace(['-', '_'], ' ', $allowedPages));
    }
}
