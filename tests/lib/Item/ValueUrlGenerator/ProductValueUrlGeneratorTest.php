<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductValueUrlGeneratorTest extends TestCase
{
    private MockObject $urlGeneratorMock;

    private ProductValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator = new ProductValueUrlGenerator($this->urlGeneratorMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator::__construct
     * @covers \Netgen\Layouts\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator::generate
     */
    public function testGenerate(): void
    {
        $this->urlGeneratorMock
            ->expects(self::once())
            ->method('generate')
            ->with(
                self::identicalTo('sylius_shop_product_show'),
                self::identicalTo(['slug' => 'product-name'])
            )
            ->willReturn('/products/product-name');

        self::assertSame(
            '/products/product-name',
            $this->urlGenerator->generate(new Product(42, 'Product name', 'product-name'))
        );
    }
}
