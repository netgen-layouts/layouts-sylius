<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Layout\Resolver\Form\TargetType\MapperInterface
     */
    private $mapper;

    public function setUp(): void
    {
        $this->mapper = new Product();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::getFormType
     */
    public function testGetFormType(): void
    {
        $this->assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::getFormOptions
     */
    public function testGetFormOptions(): void
    {
        $this->assertSame(
            [
                'item_type' => 'sylius_product',
            ],
            $this->mapper->getFormOptions()
        );
    }
}
