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
     * @param TaxonRepositoryInterface<TaxonInterface> $taxonRepository
     */
    public function __construct(private readonly TaxonRepositoryInterface $taxonRepository) {}

    public function load($id): ?TaxonInterface
    {
        try {
            $taxon = $this->taxonRepository->find($id);
        } catch (Throwable) {
            return null;
        }

        return $taxon instanceof TaxonInterface ? $taxon : null;
    }

    public function loadByRemoteId($remoteId): ?TaxonInterface
    {
        return $this->load($remoteId);
    }
}
