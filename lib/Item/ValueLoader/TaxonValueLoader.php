<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueLoader;

use Netgen\Layouts\Item\ValueLoaderInterface;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Throwable;

final class TaxonValueLoader implements ValueLoaderInterface
{
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
