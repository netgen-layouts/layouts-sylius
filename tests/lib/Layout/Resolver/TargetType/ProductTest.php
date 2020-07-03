<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\Layouts\Sylius\Tests\Validator\RepositoryValidatorFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

final class ProductTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Product\Repository\ProductRepositoryInterface
     */
    private $repositoryMock;

    /**
     * @var \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product
     */
    private $targetType;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        $this->targetType = new Product();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product::getType
     */
    public function testGetType(): void
    {
        self::assertSame('sylius_product', $this->targetType::getType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product::getConstraints
     */
    public function testValidationValid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ProductStub(42));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product::getConstraints
     */
    public function testValidationInvalid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new RepositoryValidatorFactory($this->repositoryMock))
            ->getValidator();

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product::provideValue
     */
    public function testProvideValue(): void
    {
        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_product', new ProductStub(42));

        self::assertSame(42, $this->targetType->provideValue($request));
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product::provideValue
     */
    public function testProvideValueWithNoProduct(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }
}
