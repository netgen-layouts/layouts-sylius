<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueUrlGenerator;

use Netgen\Layouts\Sylius\Item\ValueUrlGenerator\ProductValueUrlGenerator;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[CoversClass(ProductValueUrlGenerator::class)]
final class ProductValueUrlGeneratorTest extends TestCase
{
    private Stub&UrlGeneratorInterface $urlGeneratorStub;

    private ProductValueUrlGenerator $urlGenerator;

    protected function setUp(): void
    {
        $this->urlGeneratorStub = self::createStub(UrlGeneratorInterface::class);

        $this->urlGenerator = new ProductValueUrlGenerator($this->urlGeneratorStub);
    }

    public function testGenerateDefaultUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('sylius_shop_product_show'),
                self::identicalTo(['slug' => 'product-name']),
            )
            ->willReturn('/products/product-name');

        self::assertSame(
            '/products/product-name',
            $this->urlGenerator->generateDefaultUrl(new Product(42, 'Product name', 'product-name')),
        );
    }

    public function testGenerateAdminUrl(): void
    {
        $this->urlGeneratorStub
            ->method('generate')
            ->with(
                self::identicalTo('sylius_admin_product_show'),
                self::identicalTo(['id' => 42]),
            )
            ->willReturn('/admin/products/42');

        self::assertSame(
            '/admin/products/42',
            $this->urlGenerator->generateAdminUrl(new Product(42, 'Product name', 'product-name')),
        );
    }
}
