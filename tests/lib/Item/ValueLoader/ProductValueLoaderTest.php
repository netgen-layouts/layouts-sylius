<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

#[CoversClass(ProductValueLoader::class)]
final class ProductValueLoaderTest extends TestCase
{
    private Stub&ProductRepositoryInterface $productRepositoryStub;

    private ProductValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->productRepositoryStub = self::createStub(ProductRepositoryInterface::class);
        $this->valueLoader = new ProductValueLoader($this->productRepositoryStub);
    }

    public function testLoad(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->load(42));
    }

    public function testLoadWithNoProduct(): void
    {
        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo(42))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithNoProduct(): void
    {
        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->productRepositoryStub
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
