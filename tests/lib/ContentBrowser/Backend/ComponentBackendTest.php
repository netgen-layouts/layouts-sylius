<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\ContentBrowser\Backend;

use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Config\Configuration;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\ContentBrowser\Backend\ComponentBackend;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\Item;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Sylius\Tests\Stubs\Component;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use ArrayIterator;

#[CoversClass(ComponentBackend::class)]
final class ComponentBackendTest extends TestCase
{
    private MockObject&ComponentRepositoryInterface $componentRepositoryMock;

    private ComponentBackend $backend;

    protected function setUp(): void
    {
        $this->componentRepositoryMock = $this->createMock(ComponentRepositoryInterface::class);
        $localeContextMock = $this->createMock(LocaleContextInterface::class);

        $localeContextMock
            ->method('getLocaleCode')
            ->willReturn('en');

        $configuration = new Configuration(
            'sylius_component',
            'item_types.sylius_component',
            [],
            [
                'component_type_identifier' => 'component_stub',
            ],
        );

        $this->backend = new ComponentBackend(
            $this->componentRepositoryMock,
            $localeContextMock,
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
        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('load')
            ->with(self::identicalTo('component_stub'), self::identicalTo(3))
            ->willReturn(new Component(3, 'Info'));

        $item = $this->backend->loadItem('component_stub_3');

        self::assertSame('component_stub_3', $item->getValue());
        self::assertSame('Info', $item->getName());
    }

    public function testLoadItemThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Component with identifier "component_stub" and id "3" not found.');

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('load')
            ->with(self::identicalTo('component_stub'), self::identicalTo(3))
            ->willReturn(null);

        $this->backend->loadItem('component_stub_3');
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
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createListPaginator')
            ->with(self::identicalTo('component_stub'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $items = $this->backend->getSubItems(
            new RootLocation(),
        );

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testGetSubItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createListPaginator')
            ->with(self::identicalTo('component_stub'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

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
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(2);

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createListPaginator')
            ->with(self::identicalTo('component_stub'), self::identicalTo('en'))
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $count = $this->backend->getSubItemsCount(
            new RootLocation(),
        );

        self::assertSame(2, $count);
    }

    public function testSearchItems(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $searchResult = $this->backend->searchItems(new SearchQuery('component'));

        self::assertCount(2, $searchResult->getResults());
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult->getResults());
    }

    public function testSearchItemsWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $searchQuery = new SearchQuery('component');
        $searchQuery->setOffset(8);
        $searchQuery->setLimit(2);

        $searchResult = $this->backend->searchItems($searchQuery);

        self::assertCount(2, $searchResult->getResults());
        self::assertContainsOnlyInstancesOf(Item::class, $searchResult->getResults());
    }

    public function testSearchItemsCount(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(2);

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $count = $this->backend->searchItemsCount(new SearchQuery('component'));

        self::assertSame(2, $count);
    }

    public function testSearch(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(0), self::identicalTo(25))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $items = $this->backend->search('component');

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testSearchWithOffsetAndLimit(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(15);

        $pagerfantaAdapterMock
            ->method('getSlice')
            ->with(self::identicalTo(8), self::identicalTo(2))
            ->willReturn(
                new ArrayIterator([
                    new Component(2, 'My component'),
                    new Component(3, 'My other component'),
                ]),
            );

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $items = $this->backend->search('component', 8, 2);

        self::assertCount(2, $items);
        self::assertContainsOnlyInstancesOf(Item::class, $items);
    }

    public function testSearchCount(): void
    {
        $pagerfantaAdapterMock = $this->createMock(AdapterInterface::class);

        $pagerfantaAdapterMock
            ->method('getNbResults')
            ->willReturn(2);

        $this->componentRepositoryMock
            ->expects(self::once())
            ->method('createSearchPaginator')
            ->with(
                self::identicalTo('component_stub'),
                self::identicalTo('component'),
                self::identicalTo('en'),
            )
            ->willReturn(new Pagerfanta($pagerfantaAdapterMock));

        $count = $this->backend->searchCount('component');

        self::assertSame(2, $count);
    }
}
