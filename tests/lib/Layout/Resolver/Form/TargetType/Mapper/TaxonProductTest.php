<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct;
use PHPUnit\Framework\TestCase;

final class TaxonProductTest extends TestCase
{
    private TaxonProduct $mapper;

    protected function setUp(): void
    {
        $this->mapper = new TaxonProduct();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProduct::getFormOptions
     */
    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_taxon',
            ],
            $this->mapper->getFormOptions()
        );
    }
}
