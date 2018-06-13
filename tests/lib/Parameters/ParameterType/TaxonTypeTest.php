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
        $this->assertEquals('sylius_taxon', $this->type->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::configureOptions
     * @dataProvider validOptionsProvider
     */
    public function testValidOptions(array $options, array $resolvedOptions): void
    {
        $parameter = $this->getParameterDefinition($options);
        $this->assertEquals($resolvedOptions, $parameter->getOptions());
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
     * @param mixed $value
     * @param bool $required
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getValueConstraints
     * @dataProvider validationProvider
     */
    public function testValidation($value, bool $required, bool $isValid): void
    {
        if ($value !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($value))
                ->will(
                    $this->returnCallback(
                        function () use ($value): ?TaxonStub {
                            if (!is_int($value) || $value <= 0 || $value > 20) {
                                return null;
                            }

                            return new TaxonStub($value);
                        }
                    )
                );
        }

        $parameter = $this->getParameterDefinition([], $required);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate($value, $this->type->getConstraints($parameter, $value));
        $this->assertEquals($isValid, $errors->count() === 0);
    }

    public function validationProvider(): array
    {
        return [
            [12, false, true],
            [24, false, false],
            [-12, false, false],
            [0, false, false],
            ['12', false, false],
            ['', false, false],
            [null, false, true],
            [12, true, true],
            [24, true, false],
            [-12, true, false],
            [0, true, false],
            ['12', true, false],
            ['', true, false],
            [null, true, false],
        ];
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
        $this->assertEquals($isEmpty, $this->type->isValueEmpty(new ParameterDefinition(), $value));
    }

    public function emptyProvider(): array
    {
        return [
            [null, true],
            [new TaxonStub(42), false],
        ];
    }
}
