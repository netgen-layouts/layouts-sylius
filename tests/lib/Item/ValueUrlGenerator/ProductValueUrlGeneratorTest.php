<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueUrlGenerator;

use Netgen\BlockManager\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductValueUrlGeneratorTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $urlGeneratorMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator
     */
    private $urlGenerator;

    public function setUp(): void
    {
        $this->urlGeneratorMock = $this->createMock(UrlGeneratorInterface::class);

        $this->urlGenerator = new ProductValueUrlGenerator($this->urlGeneratorMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator::__construct
     * @covers \Netgen\BlockManager\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator::generate
     */
    public function testGenerate(): void
    {
        $this->urlGeneratorMock
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('sylius_shop_product_show'),
                [
                    'slug' => 'product-name',
                ]
            )
            ->will($this->returnValue('/products/product-name'));

        $this->assertSame(
            '/products/product-name',
            $this->urlGenerator->generate(new Product(42, 'Product name', 'product-name'))
        );
    }
}
