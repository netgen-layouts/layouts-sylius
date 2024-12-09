<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\SingleTaxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SingleTaxon::class)]
final class SingleTaxonTest extends TestCase
{
    private SingleTaxon $mapper;

    protected function setUp(): void
    {
        $this->mapper = new SingleTaxon();
    }

    public function testGetFormType(): void
    {
        self::assertSame(ContentBrowserType::class, $this->mapper->getFormType());
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
