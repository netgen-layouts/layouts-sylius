<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Item\ValueLoader\TaxonValueLoader;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

#[CoversClass(TaxonValueLoader::class)]
final class TaxonValueLoaderTest extends TestCase
{
    private MockObject $taxonRepositoryMock;

    private TaxonValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->valueLoader = new TaxonValueLoader($this->taxonRepositoryMock);
    }

    public function testLoad(): void
    {
        $taxon = new Taxon(42, 'Taxon name');

        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($taxon);

        self::assertSame($taxon, $this->valueLoader->load(42));
    }

    public function testLoadWithNoTaxon(): void
    {
        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo(42))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $taxon = new Taxon(42, 'Taxon name');

        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn($taxon);

        self::assertSame($taxon, $this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithNoTaxon(): void
    {
        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->taxonRepositoryMock
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
