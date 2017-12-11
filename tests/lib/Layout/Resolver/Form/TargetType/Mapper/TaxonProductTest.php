<?php

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use PHPUnit\Framework\TestCase;

class TaxonProductTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Layout\Resolver\Form\TargetType\MapperInterface
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new TaxonProduct();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct::getFormType
     */
    public function testGetFormType()
    {
        $this->assertEquals(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct::getFormOptions
     */
    public function testGetFormOptions()
    {
        $this->assertEquals(
            array(
                'item_type' => 'sylius_taxon',
            ),
            $this->mapper->getFormOptions()
        );
    }
}