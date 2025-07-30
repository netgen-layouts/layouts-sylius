<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Repository;

use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Pagerfanta\PagerfantaInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Netgen\Layouts\Sylius\Component\ComponentInterface>
 */
interface ComponentRepositoryInterface extends RepositoryInterface
{
    /**
     * Loads a component with given ID.
     */
    public function load(ComponentId $componentId): ?ComponentInterface;

    /**
     * Creates a paginator which is used to list components with a specific identifier and locale.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Netgen\Layouts\Sylius\Component\ComponentInterface>
     */
    public function createListPaginator(string $identifier, string $localeCode): PagerfantaInterface;

    /**
     * Creates a paginator which is used to search for components with a specific identifier and locale.
     *
     * @return \Pagerfanta\PagerfantaInterface<\Netgen\Layouts\Sylius\Component\ComponentInterface>
     */
    public function createSearchPaginator(string $identifier, string $searchText, string $localeCode): PagerfantaInterface;
}
