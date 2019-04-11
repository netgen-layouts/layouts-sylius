<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Taxon;
use PHPUnit\Framework\TestCase;

final class TaxonTest extends TestCase
{
    /**
     * @var \Netgen\Layouts\Layout\Resolver\Form\TargetType\MapperInterface
     */
    private $mapper;

    public function setUp(): void
    {
        $this->mapper = new Taxon();
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Taxon::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Taxon::getFormOptions
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
