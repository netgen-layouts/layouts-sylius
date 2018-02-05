<?php

namespace Netgen\BlockManager\Sylius\Tests\Parameters\ParameterType;

use Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Tests\Validator\RepositoryValidatorFactory;
use Netgen\BlockManager\Tests\Parameters\Stubs\ParameterDefinition;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Validation;

final class TaxonTypeTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp()
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getIdentifier
     */
    public function testGetIdentifier()
    {
        $type = new TaxonType();
        $this->assertEquals('sylius_taxon', $type->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::configureOptions
     * @dataProvider validOptionsProvider
     *
     * @param array $options
     * @param array $resolvedOptions
     */
    public function testValidOptions($options, $resolvedOptions)
    {
        $parameter = $this->getParameter($options);
        $this->assertEquals($resolvedOptions, $parameter->getOptions());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException
     * @dataProvider invalidOptionsProvider
     *
     * @param array $options
     */
    public function testInvalidOptions($options)
    {
        $this->getParameter($options);
    }

    /**
     * Returns the parameter under test.
     *
     * @param array $options
     * @param bool $required
     * @param mixed $defaultValue
     *
     * @return \Netgen\BlockManager\Parameters\ParameterDefinitionInterface
     */
    public function getParameter(array $options = array(), $required = false, $defaultValue = null)
    {
        return new ParameterDefinition(
            array(
                'name' => 'name',
                'type' => new TaxonType(),
                'options' => $options,
                'isRequired' => $required,
                'defaultValue' => $defaultValue,
            )
        );
    }

    /**
     * Provider for testing valid parameter attributes.
     *
     * @return array
     */
    public function validOptionsProvider()
    {
        return array(
            array(
                array(),
                array(),
            ),
        );
    }

    /**
     * Provider for testing invalid parameter attributes.
     *
     * @return array
     */
    public function invalidOptionsProvider()
    {
        return array(
            array(
                array(
                    'undefined_value' => 'Value',
                ),
            ),
        );
    }

    /**
     * @param mixed $value
     * @param bool $required
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::getValueConstraints
     * @dataProvider validationProvider
     */
    public function testValidation($value, $required, $isValid)
    {
        if ($value !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($value))
                ->will(
                    $this->returnCallback(
                        function () use ($value) {
                            if (!is_int($value) || $value <= 0 || $value > 20) {
                                return null;
                            }

                            return new TaxonStub($value);
                        }
                    )
                );
        }

        $type = new TaxonType();
        $parameter = $this->getParameter(array(), $required);
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate($value, $type->getConstraints($parameter, $value));
        $this->assertEquals($isValid, $errors->count() === 0);
    }

    /**
     * Provider for testing valid parameter values.
     *
     * @return array
     */
    public function validationProvider()
    {
        return array(
            array(12, false, true),
            array(24, false, false),
            array(-12, false, false),
            array(0, false, false),
            array('12', false, false),
            array('', false, false),
            array(null, false, true),
            array(12, true, true),
            array(24, true, false),
            array(-12, true, false),
            array(0, true, false),
            array('12', true, false),
            array('', true, false),
            array(null, true, false),
        );
    }

    /**
     * @param mixed $value
     * @param bool $isEmpty
     *
     * @covers \Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType::isValueEmpty
     * @dataProvider emptyProvider
     */
    public function testIsValueEmpty($value, $isEmpty)
    {
        $type = new TaxonType();
        $this->assertEquals($isEmpty, $type->isValueEmpty(new ParameterDefinition(), $value));
    }

    /**
     * Provider for testing if the value is empty.
     *
     * @return array
     */
    public function emptyProvider()
    {
        return array(
            array(null, true),
            array(new TaxonStub(42), false),
        );
    }
}
