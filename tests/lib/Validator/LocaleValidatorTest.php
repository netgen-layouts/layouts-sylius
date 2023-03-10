<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class LocaleValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Resource\Repository\RepositoryInterface<\Sylius\Component\Locale\Model\LocaleInterface>
     */
    private MockObject&RepositoryInterface $localeRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Locale();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->localeRepositoryMock = $this->createMock(RepositoryInterface::class);

        return new LocaleValidator($this->localeRepositoryMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateValid(): void
    {
        $locale = new LocaleStub(1, 'en_US');

        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'en_US']))
            ->willReturn($locale);

        $this->assertValid(true, 'en_US');
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->localeRepositoryMock
            ->expects(self::never())
            ->method('findOneBy');

        $this->assertValid(true, null);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\LocaleValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'fr_FR']))
            ->willReturn(null);

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
