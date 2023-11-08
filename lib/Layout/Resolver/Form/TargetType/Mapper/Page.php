<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\TargetType\Mapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use function str_replace;
use function ucfirst;

final class Page extends Mapper
{
    /**
     * @param array<string, string> $allowedPages
     */
    public function __construct(private readonly array $allowedPages) {}

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => $this->getPagesList(),
            'choice_translation_domain' => false,
            'multiple' => false,
            'expanded' => false,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getPagesList(): array
    {
        $pageList = [];

        foreach ($this->allowedPages as $page) {
            $pageList[$this->humanizePage($page)] = $page;
        }

        return $pageList;
    }

    private function humanizePage(string $allowedPages): string
    {
        return ucfirst(str_replace(['-', '_'], ' ', $allowedPages));
    }
}
