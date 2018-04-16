<?php

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Product;
use Netgen\BlockManager\Sylius\Validator\ProductValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProductValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp()
    {
        parent::setUp();

        $this->constraint = new Product();
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintValidatorInterface
     */
    public function getValidator()
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        return new ProductValidator($this->repositoryMock);
    }

    /**
     * @param int $productId
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @dataProvider validateDataProvider
     */
    public function testValidate($productId, $isValid)
    {
        if ($productId !== null) {
            $this->repositoryMock
                ->expects($this->once())
                ->method('find')
                ->with($this->equalTo($productId))
                ->will(
                    $this->returnCallback(
                        function () use ($productId) {
                            if (!is_int($productId) || $productId <= 0 || $productId > 20) {
                                return;
                            }

                            return new ProductStub($productId);
                        }
                    )
                );
        }

        $this->assertValid($isValid, $productId);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Product", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint()
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
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
