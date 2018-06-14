<?php

declare(strict_types=1);

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

    public function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Product();
    }

    public function getValidator(): ProductValidator
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        return new ProductValidator($this->repositoryMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(new ProductStub(42)));

        $this->assertValid(true, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects($this->never())
            ->method('find');

        $this->assertValid(true, null);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $this->assertValid(false, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Product", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "scalar", "array" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->assertValid(true, []);
    }
}
