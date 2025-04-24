<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\Templating\Twig\Runtime;

use Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Locale;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Intl\Locales;

#[CoversClass(SyliusRuntime::class)]
final class SyliusRuntimeTest extends TestCase
{
    private MockObject $productRepositoryMock;

    private MockObject $taxonRepositoryMock;

    private MockObject $channelRepositoryMock;

    private MockObject $localeRepositoryMock;

    private SyliusRuntime $runtime;

    protected function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->channelRepositoryMock = $this->createMock(ChannelRepositoryInterface::class);
        $this->localeRepositoryMock = $this->createMock(RepositoryInterface::class);

        $createRoutes = [
            'banner_component' => 'app_banner_component_create',
            'hero_component' => 'app_hero_component_create',
        ];

        $updateRoutes = [
            'banner_component' => 'app_banner_component_update',
            'hero_component' => 'app_hero_component_update',
        ];

        $this->runtime = new SyliusRuntime(
            $this->productRepositoryMock,
            $this->taxonRepositoryMock,
            $this->channelRepositoryMock,
            $this->localeRepositoryMock,
            $createRoutes,
            $updateRoutes,
        );
    }

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

    public function testGetProductNameWithoutProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->runtime->getProductName(42));
    }

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

    public function testGetTaxonPathWithoutTaxon(): void
    {
        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->runtime->getTaxonPath(42));
    }

    public function testGetChannelName(): void
    {
        $channel = new Channel(42, 'WEBSHOP', 'Webshop');

        $this->channelRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn($channel);

        self::assertSame('Webshop', $this->runtime->getChannelName(42));
    }

    public function testGetChannelNameWithoutChannel(): void
    {
        $this->channelRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertNull($this->runtime->getChannelName(42));
    }

    public function testGetLocaleName(): void
    {
        $locale = new Locale(5, 'en_US');

        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'en_US']))
            ->willReturn($locale);

        self::assertSame(Locales::getName('en_US'), $this->runtime->getLocaleName('en_US'));
    }

    public function testGetLocaleNameWithoutLocale(): void
    {
        $this->localeRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with(self::identicalTo(['code' => 'fr_FR']))
            ->willReturn(null);

        self::assertNull($this->runtime->getLocaleName('fr_FR'));
    }

    public function testGetComponentCreateRoute(): void
    {
        self::assertSame('app_banner_component_create', $this->runtime->getComponentCreateRoute('banner_component'));
        self::assertSame('app_hero_component_create', $this->runtime->getComponentCreateRoute('hero_component'));
        self::assertNull($this->runtime->getComponentCreateRoute('gallery_component'));
    }

    public function testGetComponentUpdateRoute(): void
    {
        self::assertSame('app_banner_component_update', $this->runtime->getComponentUpdateRoute('banner_component'));
        self::assertSame('app_hero_component_update', $this->runtime->getComponentUpdateRoute('hero_component'));
        self::assertNull($this->runtime->getComponentUpdateRoute('gallery_component'));
    }
}
