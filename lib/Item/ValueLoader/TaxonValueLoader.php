<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Throwable;

final class TaxonValueLoader implements ValueLoaderInterface
{
    /**
     * @param \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface> $taxonRepository
     */
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    public function load(int|string $id): ?TaxonInterface
    {
        try {
            return $this->taxonRepository->find($id);
        } catch (Throwable) {
            return null;
        }
    }

    public function loadByRemoteId(int|string $remoteId): ?TaxonInterface
    {
        return $this->load($remoteId);
    }
}
