<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Product;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(Product::class)]
final class ProductTest extends TestCase
{
    use ValidatorTestCaseTrait;

    private Stub&ProductRepositoryInterface $repositoryStub;

    private Product $targetType;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(ProductRepositoryInterface::class);

        $this->targetType = new Product($this->repositoryStub);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_product', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ProductStub(42));

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        $validator = $this->createValidator($this->repositoryStub);

        $errors = $validator->validate(42, $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $request = Request::create('/');
        $request->attributes->set('nglayouts_sylius_resource', new ProductStub(42));

        self::assertSame(42, $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoProduct(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }

    public function testGetValueObject(): void
    {
        $stub = new ProductStub(1);

        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn($stub);

        self::assertSame($stub, $this->targetType->getValueObject(1));
    }

    public function testGetValueObjectWithNoProduct(): void
    {
        $this->repositoryStub
            ->method('find')
            ->with(self::identicalTo(1))
            ->willReturn(null);

        self::assertNull($this->targetType->getValueObject(1));
    }
}
