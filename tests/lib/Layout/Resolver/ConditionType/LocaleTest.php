<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale;
use Netgen\Layouts\Sylius\Tests\Validator\LocaleValidatorFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;

final class LocaleTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Netgen\Layouts\Locale\LocaleProviderInterface
     */
    private MockObject $localeProviderMock;

    private Locale $conditionType;

    protected function setUp(): void
    {
        $this->localeProviderMock = $this->createMock(LocaleProviderInterface::class);

        $this->conditionType = new Locale($this->localeProviderMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale::getType
     */
    public function testGetType(): void
    {
        self::assertSame('sylius_locale', $this->conditionType::getType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale::getConstraints
     */
    public function testValidationValid(): void
    {
        $locales = [
            'en_US' => 'English (United States)',
            'en_UK' => 'English (United Kingdom)',
            'de_DE' => 'German (Germany)',
        ];

        $this->localeProviderMock
            ->expects(self::once())
            ->method('getAvailableLocales')
            ->willReturn($locales);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new LocaleValidatorFactory($this->localeProviderMock))
            ->getValidator();

        $errors = $validator->validate(['en_US'], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale::getConstraints
     */
    public function testValidationInvalidNoLocale(): void
    {
        $locales = [
            'en_US' => 'English (United States)',
            'en_UK' => 'English (United Kingdom)',
            'de_DE' => 'German (Germany)',
        ];

        $this->localeProviderMock
            ->expects(self::once())
            ->method('getAvailableLocales')
            ->willReturn($locales);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new LocaleValidatorFactory($this->localeProviderMock))
            ->getValidator();

        $errors = $validator->validate(['fr_FR'], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale::getConstraints
     */
    public function testValidationInvalidValue(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new LocaleValidatorFactory($this->localeProviderMock))
            ->getValidator();

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate([5], $this->conditionType->getConstraints());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale::matches
     */
    public function testMatches(): void
    {
        $request = Request::create('/');

        $locales = [
            'en_US',
            'en_UK',
            'de_DE',
        ];

        $this->localeProviderMock
            ->expects(self::exactly(4))
            ->method('getRequestLocales')
            ->willReturn($locales);

        self::assertTrue($this->conditionType->matches($request, ['en_US']));
        self::assertTrue($this->conditionType->matches($request, ['en_US', 'de_DE']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT', 'fr_FR']));
    }
}
