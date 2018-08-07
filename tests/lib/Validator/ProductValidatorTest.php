<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\BlockManager\Sylius\Validator\Constraint\Product;
use Netgen\BlockManager\Sylius\Validator\ProductValidator;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;

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

    public function getValidator(): ConstraintValidatorInterface
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
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->will(self::returnValue(new ProductStub(42)));

        self::assertValid(true, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects(self::never())
            ->method('find');

        self::assertValid(true, null);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->will(self::returnValue(null));

        self::assertValid(false, 42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Sylius\Validator\Constraint\Product", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->constraint = new NotBlank();
        self::assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\ProductValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "scalar", "array" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        self::assertValid(true, []);
    }
}
