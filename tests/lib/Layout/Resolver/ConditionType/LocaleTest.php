<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\ConditionType;

use Netgen\Layouts\Sylius\Layout\Resolver\ConditionType\Locale;
use Netgen\Layouts\Sylius\Tests\Stubs\Locale as LocaleStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validation;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Resource\Model\ResourceInterface>
     */
    private MockObject&RepositoryInterface $localeRepositoryMock;

    private Locale $conditionType;

    protected function setUp(): void
    {
        $this->localeRepositoryMock = $this->createMock(RepositoryInterface::class);

        $this->conditionType = new Locale();
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_locale', $this->conditionType::getType());
    }

    public function testValidationValid(): void
    {
        $locale = new LocaleStub(5, 'en_US');

        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'en_US']))
            ->willReturn($locale);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->localeRepositoryMock))
            ->getValidator();

        $errors = $validator->validate(['en_US'], $this->conditionType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalidNoLocale(): void
    {
        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'fr_FR']))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->localeRepositoryMock))
            ->getValidator();

        $errors = $validator->validate(['fr_FR'], $this->conditionType->getConstraints());
        self::assertCount(1, $errors);
    }

    public function testValidationInvalidValue(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->localeRepositoryMock))
            ->getValidator();

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate([5], $this->conditionType->getConstraints());
    }

    public function testMatches(): void
    {
        $request = Request::create('/');
        $request->setLocale('en_US');

        self::assertTrue($this->conditionType->matches($request, ['en_US']));
        self::assertTrue($this->conditionType->matches($request, ['en_US', 'de_DE']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT']));
        self::assertFalse($this->conditionType->matches($request, ['it_IT', 'fr_FR']));
    }
}
