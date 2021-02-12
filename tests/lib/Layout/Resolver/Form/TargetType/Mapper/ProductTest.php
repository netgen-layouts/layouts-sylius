<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    private Product $mapper;

    protected function setUp(): void
    {
        $this->mapper = new Product();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::getFormOptions
     */
    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_product',
            ],
            $this->mapper->getFormOptions()
        );
    }
}
