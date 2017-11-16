<?php

namespace Netgen\BlockManager\Sylius\Locale;

use Netgen\BlockManager\Locale\LocaleProviderInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface as SyliusLocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;

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

    /**
     * @var \Symfony\Component\Intl\ResourceBundle\LocaleBundleInterface
     */
    private $localeBundle;

    public function __construct(SyliusLocaleProviderInterface $syliusLocaleProvider)
    {
        $this->syliusLocaleProvider = $syliusLocaleProvider;
        $this->localeBundle = Intl::getLocaleBundle();
    }

    public function getAvailableLocales()
    {
        $availableLocales = array();

        foreach ($this->syliusLocaleProvider->getAvailableLocalesCodes() as $localeCode) {
            $localeName = $this->localeBundle->getLocaleName($localeCode);

            if ($localeName !== null) {
                $availableLocales[$localeCode] = $localeName;
            }
        }

        asort($availableLocales);

        return $availableLocales;
    }

    public function getRequestLocales(Request $request)
    {
        return array($request->getLocale());
    }
}
