<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Item\ValueConverter\ProductValueConverter;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Product as ProductStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\Product;
use Sylius\Component\Taxonomy\Model\Taxon;

#[CoversClass(ProductValueConverter::class)]
final class ProductValueConverterTest extends TestCase
{
    private ProductValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new ProductValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Product()));
        self::assertFalse($this->valueConverter->supports(new Taxon()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_product',
            $this->valueConverter->getValueType(
                new Product(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new ProductStub(42, 'Product name'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new ProductStub(42, 'Product name'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'Product name',
            $this->valueConverter->getName(
                new ProductStub(42, 'Product name'),
            ),
        );
    }

    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new ProductStub(42, 'Product name'),
            ),
        );
    }

    public function testGetIsVisibleReturnsFalse(): void
    {
        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new ProductStub(42, 'Product name', null, false),
            ),
        );
    }

    public function testGetObject(): void
    {
        $product = new ProductStub(42, 'Product name');

        self::assertSame($product, $this->valueConverter->getObject($product));
    }
}
