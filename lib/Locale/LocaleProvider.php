<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Locale;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Utils\BackwardsCompatibility\Locales;
use Sylius\Component\Locale\Provider\LocaleProviderInterface as SyliusLocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sylius specific locale provider that provides the existing locales
 * by reading them from Sylius database.
 */
final class LocaleProvider implements LocaleProviderInterface
{
    /**
     * @var \Sylius\Component\Locale\Provider\LocaleProviderInterface
     */
    private $syliusLocaleProvider;

    public function __construct(SyliusLocaleProviderInterface $syliusLocaleProvider)
    {
        $this->syliusLocaleProvider = $syliusLocaleProvider;
    }

    public function getAvailableLocales(): array
    {
        $availableLocales = [];

        foreach ($this->syliusLocaleProvider->getAvailableLocalesCodes() as $localeCode) {
            if (!Locales::exists($localeCode)) {
                continue;
            }

            $availableLocales[$localeCode] = Locales::getName($localeCode);
        }

        asort($availableLocales);

        return $availableLocales;
    }

    public function getRequestLocales(Request $request): array
    {
        return [$request->getLocale()];
    }
}
