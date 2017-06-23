<?php

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon as TaxonStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use Netgen\BlockManager\Sylius\Validator\TaxonValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaxonValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repositoryMock;

    public function setUp()
    {
        parent::setUp();

        $this->constraint = new Taxon();
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getValidator()
    {
        $this->repositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        return new TaxonValidator($this->repositoryMock);
    }

    /**
     * @param int $taxonId
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @dataProvider validateDataProvider
     */
    public function testValidate($taxonId, $isValid)
    {
        if ($taxonId !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($taxonId))
                ->will(
                    $this->returnCallback(
                        function () use ($taxonId) {
                            if (!is_int($taxonId) || $taxonId <= 0 || $taxonId > 20) {
                                return null;
                            }

                            return new TaxonStub($taxonId);
                        }
                    )
                );
        }

        $this->assertValid($isValid, $taxonId);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Taxon", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint()
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\TaxonValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "scalar", "array" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue()
    {
        $this->assertValid(true, array());
    }

    public function validateDataProvider()
    {
        return array(
            array(12, true),
            array(25, false),
            array(-12, false),
            array(0, false),
            array('12', false),
            array(null, true),
        );
    }
}
