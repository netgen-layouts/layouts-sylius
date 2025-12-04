<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Locale;

use Netgen\Layouts\Sylius\Locale\LocaleProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use function array_keys;
use function array_values;

#[CoversClass(LocaleProvider::class)]
final class LocaleProviderTest extends TestCase
{
    private Stub&LocaleProviderInterface $syliusLocaleProviderStub;

    private LocaleProvider $localeProvider;

    protected function setUp(): void
    {
        $this->syliusLocaleProviderStub = self::createStub(LocaleProviderInterface::class);

        $this->localeProvider = new LocaleProvider($this->syliusLocaleProviderStub);
    }

    public function testGetAvailableLocales(): void
    {
        $this->syliusLocaleProviderStub
            ->method('getAvailableLocalesCodes')
            ->willReturn(['en', 'de', 'hr']);

        $availableLocales = $this->localeProvider->getAvailableLocales();

        self::assertSame(['hr', 'en', 'de'], array_keys($availableLocales));
        self::assertSame(['Croatian', 'English', 'German'], array_values($availableLocales));
    }

    public function testGetRequestLocales(): void
    {
        $request = Request::create('');
        $request->setDefaultLocale('hr');

        $requestLocales = $this->localeProvider->getRequestLocales($request);

        self::assertSame(['hr'], $requestLocales);
    }
}
