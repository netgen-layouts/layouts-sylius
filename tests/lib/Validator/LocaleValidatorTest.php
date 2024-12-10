<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

#[CoversClass(LocaleValidator::class)]
final class LocaleValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Component\Locale\Model\LocaleInterface>
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

    public function testValidateNull(): void
    {
        $this->localeRepositoryMock
            ->expects(self::never())
            ->method('findOneBy');

        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'fr_FR']))
            ->willReturn(null);

        $this->assertValid(false, 'fr_FR');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Locale::class, NotBlank::class));

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "string", "array" given');

        $this->assertValid(true, []);
    }
}
