<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Locale;

use Netgen\Layouts\Sylius\Locale\LocaleProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

final class LocaleProviderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $syliusLocaleProviderMock;

    /**
     * @var \Netgen\Layouts\Sylius\Locale\LocaleProvider
     */
    private $localeProvider;

    public function setUp(): void
    {
        $this->syliusLocaleProviderMock = $this->createMock(LocaleProviderInterface::class);

        $this->localeProvider = new LocaleProvider($this->syliusLocaleProviderMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Locale\LocaleProvider::__construct
     * @covers \Netgen\Layouts\Sylius\Locale\LocaleProvider::getAvailableLocales
     */
    public function testGetAvailableLocales(): void
    {
        $this->syliusLocaleProviderMock
            ->method('getAvailableLocalesCodes')
            ->willReturn(['en', 'de', 'hr']);

        $availableLocales = $this->localeProvider->getAvailableLocales();

        self::assertSame(['hr', 'en', 'de'], array_keys($availableLocales));
        self::assertSame(['Croatian', 'English', 'German'], array_values($availableLocales));
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Locale\LocaleProvider::getRequestLocales
     */
    public function testGetRequestLocales(): void
    {
        $request = Request::create('');
        $request->setDefaultLocale('hr');

        $requestLocales = $this->localeProvider->getRequestLocales($request);

        self::assertSame(['hr'], $requestLocales);
    }
}
