<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\ProductMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProductMapper::class)]
final class ProductMapperTest extends TestCase
{
    private ProductMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new ProductMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserIntegerType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_product',
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
