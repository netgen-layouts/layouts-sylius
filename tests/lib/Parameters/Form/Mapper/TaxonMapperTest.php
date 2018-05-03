<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\BlockManager\Parameters\ParameterDefinition;
use Netgen\BlockManager\Sylius\Parameters\Form\Mapper\TaxonMapper;
use Netgen\BlockManager\Sylius\Parameters\ParameterType\TaxonType as ParameterType;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use PHPUnit\Framework\TestCase;

final class TaxonMapperTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Sylius\Parameters\Form\Mapper\TaxonMapper
     */
    private $mapper;

    public function setUp(): void
    {
        $this->mapper = new TaxonMapper();
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\Form\Mapper\TaxonMapper::getFormType
     */
    public function testGetFormType(): void
    {
        $this->assertEquals(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Parameters\Form\Mapper\TaxonMapper::mapOptions
     */
    public function testMapOptions(): void
    {
        $this->assertEquals(
            [
                'item_type' => 'sylius_taxon',
                'required' => false,
            ],
            $this->mapper->mapOptions(new ParameterDefinition(['type' => new ParameterType(), 'isRequired' => false]))
        );
    }
}
