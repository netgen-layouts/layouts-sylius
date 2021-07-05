<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class LocaleTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Netgen\Layouts\Locale\LocaleProviderInterface
     */
    private MockObject $localeProvider;

    private Locale $mapper;

    protected function setUp(): void
    {
        $this->localeProvider = $this->createMock(LocaleProviderInterface::class);

        $this->mapper = new Locale(
            $this->localeProvider,
        );
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale::getFormOptions
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale::getLocaleList
     */
    public function testGetFormOptions(): void
    {
        $locales = [
            'en_US' => 'English (United States)',
            'en_UK' => 'English (United Kingdom)',
            'de_DE' => 'German (Germany)',
        ];

        $localeList = [
            'English (United States)' => 'en_US',
            'English (United Kingdom)' => 'en_UK',
            'German (Germany)' => 'de_DE',
        ];

        $this->localeProvider
            ->expects(self::once())
            ->method('getAvailableLocales')
            ->willReturn($locales);

        self::assertSame(
            [
                'choices' => $localeList,
                'choice_translation_domain' => false,
                'multiple' => true,
                'expanded' => true,
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
