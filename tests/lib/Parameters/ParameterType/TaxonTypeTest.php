<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\ParameterType\TaxonType;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use Netgen\Layouts\Tests\Parameters\ParameterType\ParameterTypeTestTrait;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;

#[CoversClass(TaxonType::class)]
final class TaxonTypeTest extends TestCase
{
    use ParameterTypeTestTrait;
    use ValidatorTestCaseTrait;

    private Stub&TaxonRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $this->createValidator();

        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);

        $this->type = new TaxonType($this->repositoryStub);
    }

    public function testGetIdentifier(): void
    {
        self::assertSame('sylius_taxon', $this->type::getIdentifier());
    }

    public function testFromHash(): void
    {
        self::assertSame(42, $this->type->fromHash(new ParameterDefinition(), '42'));
    }

    public function testFromHashWithNullValue(): void
    {
        self::assertNull($this->type->fromHash(new ParameterDefinition(), null));
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $resolvedOptions
     */
    #[DataProvider('validOptionsDataProvider')]
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameterDefinition = $this->getParameterDefinition($options);
        self::assertSame($resolvedOptions, $parameterDefinition->options);
    }

    /**
     * @param mixed[] $options
     */
    #[DataProvider('invalidOptionsDataProvider')]
    public function testInvalidOptions(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getParameterDefinition($options);
    }

    public static function validOptionsDataProvider(): iterable
    {
        return [
            [
                [],
                [],
            ],
        ];
    }

    public static function invalidOptionsDataProvider(): iterable
    {
        return [
            [
                [
                    'undefined_value' => 'Value',
                ],
            ],
        ];
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new TaxonStub(42));

        $parameter = $this->getParameterDefinition([], true);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(42, $this->type->getConstraints($parameter, 42));
        self::assertCount(0, $errors);
    }

    public function testValidationValidWithNonRequiredValue(): void
    {
        $parameter = $this->getParameterDefinition();
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryStub))
            ->getValidator();

        $errors = $validator->validate(null, $this->type->getConstraints($parameter, null));
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $parameterDefinition = $this->getParameterDefinition([], true);

        $errors = $this->validator->validate(42, $this->type->getConstraints($parameterDefinition, 42));
        self::assertNotCount(0, $errors);
    }

    #[DataProvider('emptyDataProvider')]
    public function testIsValueEmpty(mixed $value, bool $isEmpty): void
    {
        self::assertSame($isEmpty, $this->type->isValueEmpty(new ParameterDefinition(), $value));
    }

    public static function emptyDataProvider(): iterable
    {
        return [
            [null, true],
            [new TaxonStub(42), false],
        ];
    }

    public function testGetValueObject(): void
    {
        $stub = new TaxonStub(1);

        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn($stub);

        /** @var \Netgen\Layouts\Sylius\Parameters\ParameterType\TaxonType $type */
        $type = $this->type;

        self::assertSame($stub, $type->getValueObject(1));
    }
}
