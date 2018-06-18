<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\Templating\Twig\Runtime;

use Netgen\BlockManager\Sylius\Tests\Stubs\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
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
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime
     */
    private $runtime;

    public function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);

        $this->runtime = new SyliusRuntime(
            $this->productRepositoryMock,
            $this->taxonRepositoryMock
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::getProductName
     */
    public function testGetProductName(): void
    {
        $product = new Product(42);
        $product->setCurrentLocale('en');
        $product->setName('Product name');

        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue($product));

        $this->assertSame('Product name', $this->runtime->getProductName(42));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::getProductName
     */
    public function testGetProductNameWithoutProduct(): void
    {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $this->assertNull($this->runtime->getProductName(42));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::getTaxonPath
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
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue($taxon1));

        $this->assertSame(['Taxon 44', 'Taxon 43', 'Taxon 42'], $this->runtime->getTaxonPath(42));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime::getTaxonPath
     */
    public function testGetTaxonPathWithoutTaxon(): void
    {
        $this->taxonRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($this->equalTo(42))
            ->will($this->returnValue(null));

        $this->assertNull($this->runtime->getTaxonPath(42));
    }
}
