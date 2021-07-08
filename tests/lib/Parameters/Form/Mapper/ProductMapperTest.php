<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Sylius\Parameters\Form\Mapper\ProductMapper;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ProductType as ParameterType;
use PHPUnit\Framework\TestCase;

final class ProductMapperTest extends TestCase
{
    private ProductMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new ProductMapper();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\Form\Mapper\ProductMapper::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Parameters\Form\Mapper\ProductMapper::mapOptions
     */
    public function testMapOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_product',
                'required' => false,
            ],
            $this->mapper->mapOptions(ParameterDefinition::fromArray(['type' => new ParameterType(), 'isRequired' => false])),
        );
    }
}
