<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

#[CoversClass(ProductValueLoader::class)]
final class ProductValueLoaderTest extends TestCase
{
    private MockObject&ProductRepositoryInterface $productRepositoryMock;

    private ProductValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->valueLoader = new ProductValueLoader($this->productRepositoryMock);
    }

    public function testLoad(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->load(42));
    }

    public function testLoadWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
