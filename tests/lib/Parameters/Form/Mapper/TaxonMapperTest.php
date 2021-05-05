<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\TaxonMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\TaxonType as ParameterType;
use PHPUnit\Framework\TestCase;

final class TaxonMapperTest extends TestCase
{
    private TaxonMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new TaxonMapper();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\Form\Mapper\TaxonMapper::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\Form\Mapper\TaxonMapper::mapOptions
     */
    public function testMapOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_taxon',
                'required' => false,
            ],
            $this->mapper->mapOptions(ParameterDefinition::fromArray(['type' => new ParameterType(), 'isRequired' => false])),
        );
    }
}
