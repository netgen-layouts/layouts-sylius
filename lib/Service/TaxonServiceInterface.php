<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Service;

use Sylius\Component\Taxonomy\Model\TaxonInterface;

interface TaxonServiceInterface
{
    public function countTaxonChildren(TaxonInterface $parent): int;
}
