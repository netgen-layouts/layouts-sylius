<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\Layouts\Sylius\Validator\Constraint\Product;
use Netgen\Layouts\Sylius\Validator\ProductValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

#[CoversClass(ProductValidator::class)]
final class ProductValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Product\Repository\ProductRepositoryInterface<\Sylius\Component\Product\Model\ProductInterface>
     */
    private Stub&ProductRepositoryInterface $repositoryStub;

    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Product();
    }

    public function testValidateValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(new ProductStub(42));

        $this->assertValid(true, 42);
    }

    public function testValidateNull(): void
    {
        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        $this->assertValid(false, 42);
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', Product::class, NotBlank::class));

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "int", "array" given');

        $this->assertValid(true, []);
    }

    protected function getConstraintValidator(): ConstraintValidatorInterface
    {
        $this->repositoryStub = self::createStub(ProductRepositoryInterface::class);

        return new ProductValidator($this->repositoryStub);
    }
}
