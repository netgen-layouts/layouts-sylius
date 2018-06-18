<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

final class ProductValueLoaderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader
     */
    private $valueLoader;

    public function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);

        $this->valueLoader = new ProductValueLoader($this->productRepositoryMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::__construct
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     */
    public function testLoad(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue($product));

        $this->assertSame($product, $this->valueLoader->load(42));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     * @expectedException \Netgen\BlockManager\Exception\Item\ItemException
     * @expectedExceptionMessage Value with (remote) ID 42 does not exist.
     */
    public function testLoadThrowsItemExceptionWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $this->valueLoader->load(42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     * @expectedException \Netgen\BlockManager\Exception\Item\ItemException
     * @expectedExceptionMessage Value with (remote) ID 42 does not exist.
     */
    public function testLoadThrowsItemExceptionWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->throwException(new Exception()));

        $this->valueLoader->load(42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     */
    public function testLoadByRemoteId(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo('abc'))
            ->will($this->returnValue($product));

        $this->assertSame($product, $this->valueLoader->loadByRemoteId('abc'));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     * @expectedException \Netgen\BlockManager\Exception\Item\ItemException
     * @expectedExceptionMessage Value with (remote) ID abc does not exist.
     */
    public function testLoadByRemoteIdThrowsItemExceptionWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo('abc'))
            ->will($this->returnValue(null));

        $this->valueLoader->loadByRemoteId('abc');
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     * @expectedException \Netgen\BlockManager\Exception\Item\ItemException
     * @expectedExceptionMessage Value with (remote) ID abc does not exist.
     */
    public function testLoadByRemoteIdThrowsItemExceptionWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo('abc'))
            ->will($this->throwException(new Exception()));

        $this->valueLoader->loadByRemoteId('abc');
    }
}
