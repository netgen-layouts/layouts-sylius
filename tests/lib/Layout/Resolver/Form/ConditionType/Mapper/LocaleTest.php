<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale;
use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Locales;

final class LocaleTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Resource\Repository\RepositoryInterface
     */
    private MockObject $localeRepositoryMock;

    private Locale $mapper;

    protected function setUp(): void
    {
        $this->localeRepositoryMock = $this->createMock(RepositoryInterface::class);

        $this->mapper = new Locale(
            $this->localeRepositoryMock,
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
            new LocaleStub(1, 'en_US'),
            new LocaleStub(2, 'it_IT'),
            new LocaleStub(3, 'de_DE'),
        ];

        $localeList = [
            Locales::getName('en_US') => 'en_US',
            Locales::getName('it_IT') => 'it_IT',
            Locales::getName('de_DE') => 'de_DE',
        ];

        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findAll')
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
