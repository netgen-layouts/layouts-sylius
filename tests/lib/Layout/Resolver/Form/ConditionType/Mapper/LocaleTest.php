<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Locale;
use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Locales;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    private Stub&RepositoryInterface $localeRepositoryStub;

    private Locale $mapper;

    protected function setUp(): void
    {
        $this->localeRepositoryStub = self::createStub(RepositoryInterface::class);

        $this->mapper = new Locale(
            $this->localeRepositoryStub,
        );
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

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

        $this->localeRepositoryStub
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
