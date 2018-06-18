<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\BlockManager\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

final class ProductTest extends TestCase
{
    /**
     * @var \Sylius\Component\Product\Repository\ProductRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product
     */
    private $targetType;

    public function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        $this->targetType = new Product();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product::getType
     */
    public function testGetType(): void
    {
        $this->assertSame('sylius_product', $this->targetType->getType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product::getConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(new ProductStub(42)));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        $this->assertTrue($errors->count() === 0);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product::getConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        $this->assertFalse($errors->count() === 0);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product::provideValue
     */
    public function testProvideValue(): void
    {
        $request = Request::create('/');
        $request->attributes->set('ngbm_sylius_product', new ProductStub(42));

        $this->assertSame(42, $this->targetType->provideValue($request));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetType\Product::provideValue
     */
    public function testProvideValueWithNoProduct(): void
    {
        $request = Request::create('/');

        $this->assertNull($this->targetType->provideValue($request));
    }
}
