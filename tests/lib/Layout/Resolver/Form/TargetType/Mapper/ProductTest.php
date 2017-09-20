<?php

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Layout\Resolver\Form\TargetType\MapperInterface
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new Product();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::getFormType
     */
    public function testGetFormType()
    {
        $this->assertEquals(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\Product::mapOptions
     */
    public function testMapOptions()
    {
        $this->assertEquals(
            array(
                'item_type' => 'sylius_product',
            ),
            $this->mapper->mapOptions(
                $this->createMock(TargetTypeInterface::class)
            )
        );
    }
}
