<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\Templating\Twig\Runtime;

use Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class SyliusRuntimeTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepositoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $taxonRepositoryMock;

    /**
     * @var \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime
     */
    private $runtime;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->runtime = new SyliusRuntime(
            $this->productRepositoryMock,
            $this->taxonRepositoryMock
        );
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::__construct
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::getProductName
     */
    public function testGetProductName(): void
    {
        $product = new Product(42);
        $product->setCurrentLocale('en');
        $product->setName('Product name');

        $this->productRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($product);

        self::assertSame('Product name', $this->runtime->getProductName(42));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::getProductName
     */
    public function testGetProductNameWithoutProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->runtime->getProductName(42));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::__construct
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::getTaxonPath
     */
    public function testGetTaxonPath(): void
    {
        $taxon1 = new Taxon(42);
        $taxon1->setCurrentLocale('en');
        $taxon1->setName('Taxon 42');

        $taxon2 = new Taxon(43);
        $taxon2->setCurrentLocale('en');
        $taxon2->setName('Taxon 43');

        $taxon3 = new Taxon(44);
        $taxon3->setCurrentLocale('en');
        $taxon3->setName('Taxon 44');

        $taxon1->setParent($taxon2);
        $taxon2->setParent($taxon3);

        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($taxon1);

        self::assertSame(['Taxon 44', 'Taxon 43', 'Taxon 42'], $this->runtime->getTaxonPath(42));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime::getTaxonPath
     */
    public function testGetTaxonPathWithoutTaxon(): void
    {
        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->runtime->getTaxonPath(42));
    }
}
