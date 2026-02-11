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
    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Product\Repository\ProductRepositoryInterface<\Sylius\Component\Product\Model\ProductInterface>
     */
    private Stub&ProductRepositoryInterface $repositoryStub;

    private ProductValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(ProductRepositoryInterface::class);
        $this->valueLoader = new ProductValueLoader($this->repositoryStub);
    }

    public function testLoad(): void
    {
        $product = new Product(42, 'Product name');

        $this->repositoryStub
            ->method('find')
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->load(42));
    }

    public function testLoadWithNoProduct(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $product = new Product(42, 'Product name');

        $this->repositoryStub
            ->method('find')
            ->willReturn($product);

        self::assertSame($product, $this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithNoProduct(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
