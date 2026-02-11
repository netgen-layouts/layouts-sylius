<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\Layouts\Sylius\Item\ValueLoader\TaxonValueLoader;
use Netgen\Layouts\Sylius\Tests\Item\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

#[CoversClass(TaxonValueLoader::class)]
final class TaxonValueLoaderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\Stub&\Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface>
     */
    private Stub&TaxonRepositoryInterface $repositoryStub;

    private TaxonValueLoader $valueLoader;

    protected function setUp(): void
    {
        $this->repositoryStub = self::createStub(TaxonRepositoryInterface::class);
        $this->valueLoader = new TaxonValueLoader($this->repositoryStub);
    }

    public function testLoad(): void
    {
        $taxon = new Taxon(42, 'Taxon name');

        $this->repositoryStub
            ->method('find')
            ->willReturn($taxon);

        self::assertSame($taxon, $this->valueLoader->load(42));
    }

    public function testLoadWithNoTaxon(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadWithRepositoryException(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->load(42));
    }

    public function testLoadByRemoteId(): void
    {
        $taxon = new Taxon(42, 'Taxon name');

        $this->repositoryStub
            ->method('find')
            ->willReturn($taxon);

        self::assertSame($taxon, $this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithNoTaxon(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willReturn(null);

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->repositoryStub
            ->method('find')
            ->willThrowException(new Exception());

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
