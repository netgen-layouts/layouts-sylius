<?php

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueConverter;

use Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product as ProductStub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\Product;
use Sylius\Component\Taxonomy\Model\Taxon;

final class ProductValueConverterTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter
     */
    private $valueConverter;

    public function setUp()
    {
        $this->valueConverter = new ProductValueConverter();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->valueConverter->supports(new Product()));
        $this->assertFalse($this->valueConverter->supports(new Taxon()));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getValueType
     */
    public function testGetValueType()
    {
        $this->assertEquals(
            'sylius_product',
            $this->valueConverter->getValueType(
                new Product()
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getId
     */
    public function testGetId()
    {
        $this->assertEquals(
            42,
            $this->valueConverter->getId(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getRemoteId
     */
    public function testGetRemoteId()
    {
        $this->assertEquals(
            42,
            $this->valueConverter->getRemoteId(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getName
     */
    public function testGetName()
    {
        $this->assertEquals(
            'Product name',
            $this->valueConverter->getName(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getIsVisible
     */
    public function testGetIsVisible()
    {
        $this->assertTrue(
            $this->valueConverter->getIsVisible(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getObject
     */
    public function testGetObject()
    {
        $product = new ProductStub(42, 'Product name');

        $this->assertEquals($product, $this->valueConverter->getObject($product));
    }
}
