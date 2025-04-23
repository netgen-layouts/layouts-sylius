<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Repository;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Pagerfanta\PagerfantaInterface;

interface ComponentRepositoryInterface
{
    /**
     * Loads single component with given type identifier and ID.
     */
    public function load(string $identifier, int $id): ?ComponentInterface;

    /**
     * Creates a paginator which is used to list components with a specific identifier and locale.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Netgen\Layouts\Sylius\API\ComponentInterface>
     */
    public function createListPaginator(string $identifier, string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for components with a specific identifier and locale.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Netgen\Layouts\Sylius\API\ComponentInterface>
     */
    public function createSearchPaginator(string $identifier, string $searchText, string $localeCode): PagerfantaInterface;
}
