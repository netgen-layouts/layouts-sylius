<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class LocaleValidatorTest extends ValidatorTestCase
{
    private MockObject $localeProviderMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Locale();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->localeProviderMock = $this->createMock(LocaleProviderInterface::class);

        return new LocaleValidator($this->localeProviderMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateValid(): void
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

        $this->assertValid(true, 'en_US');
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->localeProviderMock
            ->expects(self::never())
            ->method('getAvailableLocales');

        $this->assertValid(true, null);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->localeProviderMock
            ->expects(self::once())
            ->method('getAvailableLocales')
            ->willReturn([]);

        $this->assertValid(false, 'fr_FR');
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\\Layouts\\Sylius\\Validator\\Constraint\\Locale", "Symfony\\Component\\Validator\\Constraints\\NotBlank" given');

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "string", "array" given');

        $this->assertValid(true, []);
    }
}
