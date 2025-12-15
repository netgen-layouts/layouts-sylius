<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\TaxonProductMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TaxonProductMapper::class)]
final class TaxonProductMapperTest extends TestCase
{
    private TaxonProductMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new TaxonProductMapper();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserIntegerType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'item_type' => 'sylius_taxon',
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
