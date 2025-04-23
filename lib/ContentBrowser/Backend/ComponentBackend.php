<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\ContentBrowser\Backend;

use Netgen\ContentBrowser\Backend\BackendInterface;
use Netgen\ContentBrowser\Backend\SearchQuery;
use Netgen\ContentBrowser\Backend\SearchResult;
use Netgen\ContentBrowser\Backend\SearchResultInterface;
use Netgen\ContentBrowser\Config\Configuration;
use Netgen\ContentBrowser\Exceptions\NotFoundException;
use Netgen\ContentBrowser\Item\LocationInterface;
use Netgen\Layouts\Browser\Item\Layout\RootLocation;
use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\Item;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\ItemValue;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

use function sprintf;

final class ComponentBackend implements BackendInterface
{
    public function __construct(
        private ComponentRepositoryInterface $componentRepository,
        private LocaleContextInterface $localeContext,
        private Configuration $config,
    ) {
    }

    public function getSections(): iterable
    {
        return [new RootLocation()];
    }

    public function loadLocation($id): RootLocation
    {
        return new RootLocation();
    }

    public function loadItem($value): Item
    {
        $itemValue = ItemValue::fromValue((string) $value);

        $component = $this->componentRepository->load($itemValue->getComponentTypeIdentifier(), $itemValue->getId());

        if (!$component instanceof ComponentInterface) {
            throw new NotFoundException(
                sprintf(
                    'Component with identifier "%s" and id "%d" not found.',
                    $itemValue->getComponentTypeIdentifier(),
                    $itemValue->getId(),
                ),
            );
        }

        return $this->buildItem($component);
    }

    public function getSubLocations(LocationInterface $location): iterable
    {
        return [];
    }

    public function getSubLocationsCount(LocationInterface $location): int
    {
        return 0;
    }

    public function getSubItems(LocationInterface $location, int $offset = 0, int $limit = 25): iterable
    {
        if (!$this->config->hasParameter('component_type_identifier')) {
            return [];
        }

        $paginator = $this->componentRepository->createListPaginator(
            $this->config->getParameter('component_type_identifier'),
            $this->localeContext->getLocaleCode(),
        );

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage((int) ($offset / $limit) + 1);

        return $this->buildItems(
            $paginator->getCurrentPageResults(),
        );
    }

    public function getSubItemsCount(LocationInterface $location): int
    {
        if (!$this->config->hasParameter('component_type_identifier')) {
            return 0;
        }

        $paginator = $this->componentRepository->createListPaginator(
            $this->config->getParameter('component_type_identifier'),
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    public function searchItems(SearchQuery $searchQuery): SearchResultInterface
    {
        if (!$this->config->hasParameter('component_type_identifier')) {
            return new SearchResult([]);
        }

        $paginator = $this->componentRepository->createSearchPaginator(
            $this->config->getParameter('component_type_identifier'),
            $searchQuery->getSearchText(),
            $this->localeContext->getLocaleCode(),
        );

        $paginator->setMaxPerPage($searchQuery->getLimit());
        $paginator->setCurrentPage((int) ($searchQuery->getOffset() / $searchQuery->getLimit()) + 1);

        return new SearchResult(
            $this->buildItems(
                $paginator->getCurrentPageResults(),
            ),
        );
    }

    public function searchItemsCount(SearchQuery $searchQuery): int
    {
        if (!$this->config->hasParameter('component_type_identifier')) {
            return 0;
        }

        $paginator = $this->componentRepository->createSearchPaginator(
            $this->config->getParameter('component_type_identifier'),
            $searchQuery->getSearchText(),
            $this->localeContext->getLocaleCode(),
        );

        return $paginator->getNbResults();
    }

    public function search(string $searchText, int $offset = 0, int $limit = 25): iterable
    {
        $searchQuery = new SearchQuery($searchText);
        $searchQuery->setOffset($offset);
        $searchQuery->setLimit($limit);

        $searchResult = $this->searchItems($searchQuery);

        return $searchResult->getResults();
    }

    public function searchCount(string $searchText): int
    {
        return $this->searchItemsCount(new SearchQuery($searchText));
    }

    /**
     * Builds the item from provided component.
     */
    private function buildItem(ComponentInterface $component): Item
    {
        return new Item($component);
    }

    /**
     * Builds the items from provided components.
     *
     * @param iterable<\Netgen\Layouts\Sylius\API\ComponentInterface> $components
     *
     * @return \Netgen\Layouts\Sylius\ContentBrowser\Item\Component\Item[]
     */
    private function buildItems(iterable $components): array
    {
        $items = [];

        foreach ($components as $component) {
            $items[] = $this->buildItem($component);
        }

        return $items;
    }
}
