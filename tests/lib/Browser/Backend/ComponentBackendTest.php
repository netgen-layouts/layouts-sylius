<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Browser\Backend;

use ArrayIterator;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Config\Configuration;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Sylius\Browser\Backend\ComponentBackend;
use Netgen\Layouts\Sylius\Browser\Item\Component\Item;
use Netgen\Layouts\Sylius\Browser\Item\Component\RootLocation;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Sylius\Tests\Stubs\Component;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;

#[CoversClass(ComponentBackend::class)]
final class ComponentBackendTest extends TestCase
{
    private Stub&ComponentRepositoryInterface $componentRepositoryStub;

    private ComponentBackend $backend;

    protected function setUp(): void
    {
        $this->componentRepositoryStub = self::createStub(ComponentRepositoryInterface::class);
        $localeContextStub = self::createStub(LocaleContextInterface::class);

        $localeContextStub
            ->method('getLocaleCode')
            ->willReturn('en');

        $configuration = new Configuration(
            'sylius_component',
            'item_types.sylius_component',
            [],
        );

        $configuration->setParameter('component_type', 'component_stub');

        $this->backend = new ComponentBackend(
            $this->componentRepositoryStub,
            $localeContextStub,
            $configuration,
        );
    }

    public function testGetSections(): void
    {
        $locations = $this->backend->getSections();

        self::assertCount(1, $locations);
        self::assertContainsOnlyInstancesOf(RootLocation::class, $locations);
    }

    public function testLoadItem(): void
    {
        $componentId = new ComponentId('component_stub', 3);

        $this->componentRepositoryStub
            ->method('load')
            ->willReturn(new Component(3, 'Info'));

        $item = $this->backend->loadItem('component_stub-3');

        self::assertSame('component_stub-3', $item->value);
        self::assertSame('Info', $item->name);
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Component with type "component_stub" and id "3" not found.');

        $componentId = new ComponentId('component_stub', 3);

        $this->componentRepositoryStub
            ->method('load')
            ->willReturn(null);

        $this->backend->loadItem('component_stub-3');
    }

    public function testGetSubLocations(): void
    {
        $locations = $this->backend->getSubLocations(new RootLocation());

        self::assertCount(0, $locations);
    }

    public function testGetSubLocationsCount(): void
    {
        $count = $this->backend->getSubLocationsCount(new RootLocation());

        self::assertSame(0, $count);
    }

    public function testGetSubItems(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $items = $this->backend->getSubItems(
            new RootLocation(),
        );

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testGetSubItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $items = $this->backend->getSubItems(
            new RootLocation(),
            8,
            2,
        );

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testGetSubItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->componentRepositoryStub
            ->method('createListPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->getSubItemsCount(
            new RootLocation(),
        );

        self::assertSame(2, $count);
    }

    public function testSearchItems(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $searchResult = $this->backend->searchItems(new SearchQuery('component'));

        self::assertCount(2, $searchResult->results);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult->results);
    }

    public function testSearchItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterStub
            ->method('getSlice')
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $searchQuery = new SearchQuery('component');
        $searchQuery->offset = 8;
        $searchQuery->limit = 2;

        $searchResult = $this->backend->searchItems($searchQuery);

        self::assertCount(2, $searchResult->results);
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult->results);
    }

    public function testSearchItemsCount(): void
    {
        $pagerfantaAdapterStub = self::createStub(AdapterInterface::class);

        $pagerfantaAdapterStub
            ->method('getNbResults')
            ->willReturn(2);

        $this->componentRepositoryStub
            ->method('createSearchPaginator')
            ->willReturn(new Pagerfanta($pagerfantaAdapterStub));

        $count = $this->backend->searchItemsCount(new SearchQuery('component'));

        self::assertSame(2, $count);
    }
}
