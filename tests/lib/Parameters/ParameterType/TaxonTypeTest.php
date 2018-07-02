<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Parameters\ParameterType;

use Netgen\BlockManager\Parameters\ParameterDefinition;
use Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Tests\Validator\RepositoryValidatorFactory;
use Netgen\BlockManager\Tests\Parameters\ParameterType\ParameterTypeTestTrait;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Validation;

final class TaxonTypeTest extends TestCase
{
    use ParameterTypeTestTrait;

    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp(): void
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->type = new TaxonType();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getIdentifier
     */
    public function testGetIdentifier(): void
    {
        $this->assertSame('sylius_taxon', $this->type->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::configureOptions
     * @dataProvider validOptionsProvider
     */
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameter = $this->getParameterDefinition($options);
        $this->assertSame($resolvedOptions, $parameter->getOptions());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException
     * @dataProvider invalidOptionsProvider
     */
    public function testInvalidOptions(array $options): void
    {
        $this->getParameterDefinition($options);
    }

    /**
     * Provider for testing valid parameter attributes.
     */
    public function validOptionsProvider(): array
    {
        return [
            [
                [],
                [],
            ],
        ];
    }

    /**
     * Provider for testing invalid parameter attributes.
     */
    public function invalidOptionsProvider(): array
    {
        return [
            [
                [
                    'undefined_value' => 'Value',
                ],
            ],
        ];
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getValueConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo(42))
                ->will($this->returnValue(new TaxonStub(42)));

        $parameter = $this->getParameterDefinition([], true);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->type->getConstraints($parameter, 42));
        $this->assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getValueConstraints
     */
    public function testValidationValidWithNonRequiredValue(): void
    {
        $this->repositoryMock
                ->expects($this->never())
                ->method('find');

        $parameter = $this->getParameterDefinition([], false);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(null, $this->type->getConstraints($parameter, null));
        $this->assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getValueConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo(42))
                ->will($this->returnValue(null));

        $parameter = $this->getParameterDefinition([], true);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->type->getConstraints($parameter, 42));
        $this->assertNotCount(0, $errors);
    }

    /**
     * @param mixed $value
     * @param bool $isEmpty
     *
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::isValueEmpty
     * @dataProvider emptyProvider
     */
    public function testIsValueEmpty($value, bool $isEmpty): void
    {
        $this->assertSame($isEmpty, $this->type->isValueEmpty(new ParameterDefinition(), $value));
    }

    public function emptyProvider(): array
    {
        return [
            [null, true],
            [new TaxonStub(42), false],
        ];
    }
}
