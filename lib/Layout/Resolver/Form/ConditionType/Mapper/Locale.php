<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Netgen\Layouts\Locale\LocaleProviderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class Locale extends Mapper
{
    private LocaleProviderInterface $localeProvider;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => $this->getLocaleList(),
            'choice_translation_domain' => false,
            'multiple' => true,
            'expanded' => true,
        ];
    }

    private function getLocaleList(): array
    {
        $locales = $this->localeProvider->getAvailableLocales();

        $localeList = [];

        foreach ($locales as $locale => $name) {
            $localeList[$name] = $locale;
        }

        return $localeList;
    }
}
