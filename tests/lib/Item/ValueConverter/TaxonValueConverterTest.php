<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueConverter;

use Netgen\Layouts\Sylius\Item\ValueConverter\TaxonValueConverter;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Taxon as TaxonStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\Product;
use Sylius\Component\Taxonomy\Model\Taxon;

#[CoversClass(TaxonValueConverter::class)]
final class TaxonValueConverterTest extends TestCase
{
    private TaxonValueConverter $valueConverter;

    protected function setUp(): void
    {
        $this->valueConverter = new TaxonValueConverter();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->valueConverter->supports(new Taxon()));
        self::assertFalse($this->valueConverter->supports(new Product()));
    }

    public function testGetValueType(): void
    {
        self::assertSame(
            'sylius_taxon',
            $this->valueConverter->getValueType(
                new Taxon(),
            ),
        );
    }

    public function testGetId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getId(
                new TaxonStub(42, 'Taxon name'),
            ),
        );
    }

    public function testGetRemoteId(): void
    {
        self::assertSame(
            42,
            $this->valueConverter->getRemoteId(
                new TaxonStub(42, 'Taxon name'),
            ),
        );
    }

    public function testGetName(): void
    {
        self::assertSame(
            'Taxon name',
            $this->valueConverter->getName(
                new TaxonStub(42, 'Taxon name'),
            ),
        );
    }

    public function testGetIsVisible(): void
    {
        self::assertTrue(
            $this->valueConverter->getIsVisible(
                new TaxonStub(42, 'Taxon name'),
            ),
        );
    }

    public function testGetIsVisibleReturnsFalse(): void
    {
        self::assertFalse(
            $this->valueConverter->getIsVisible(
                new TaxonStub(42, 'Taxon name', null, false),
            ),
        );
    }

    public function testGetObject(): void
    {
        $taxon = new TaxonStub(42, 'Taxon name');

        self::assertSame($taxon, $this->valueConverter->getObject($taxon));
    }
}
