<?php

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueLoader;

use Netgen\BlockManager\Exception\InvalidItemException;
use Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

class ProductValueLoaderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productRepositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader
     */
    protected $valueLoader;

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
     * @expectedException \Netgen\BlockManager\Exception\InvalidItemException
     * @expectedExceptionMessage Product with ID 42 could not be loaded.
     */
    public function testLoadThrowsInvalidItemException()
    {
        $this->productRepositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->throwException(new InvalidItemException()));

        $this->valueLoader->load(42);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::getValueType
     */
    public function testGetValueType()
    {
        $this->assertEquals('sylius_product', $this->valueLoader->getValueType());
    }
}
