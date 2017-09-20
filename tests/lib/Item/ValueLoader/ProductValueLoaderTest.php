<?php

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

class ProductValueLoaderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader
     */
    private $valueLoader;

    public function setUp()
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);

        $this->valueLoader = new ProductValueLoader($this->productRepositoryMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::__construct
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     */
    public function testLoad()
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue($product));

        $this->assertEquals($product, $this->valueLoader->load(42));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     * @expectedException \Netgen\BlockManager\Exception\Item\ItemException
     * @expectedExceptionMessage Value with ID 42 does not exist.
     */
    public function testLoadThrowsItemExceptionWithNoProduct()
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
     * @expectedExceptionMessage Value with ID 42 does not exist.
     */
    public function testLoadThrowsItemExceptionWithRepositoryException()
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->throwException(new Exception()));

        $this->valueLoader->load(42);
    }
}
