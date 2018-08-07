<?php

declare(strict_types=1);

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

    public function setUp(): void
    {
        $this->valueConverter = new ProductValueConverter();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::supports
     */
    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Product()));
        self::assertFalse($this->valueConverter->supports(new Taxon()));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getValueType
     */
    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_product',
            $this->valueConverter->getValueType(
                new Product()
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getId
     */
    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getRemoteId
     */
    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getName
     */
    public function testGetName(): void
    {
        self::assertSame(
            'Product name',
            $this->valueConverter->getName(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getIsVisible
     */
    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new ProductStub(42, 'Product name')
            )
        );
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueConverter\ProductValueConverter::getObject
     */
    public function testGetObject(): void
    {
        $product = new ProductStub(42, 'Product name');

        self::assertSame($product, $this->valueConverter->getObject($product));
    }
}
