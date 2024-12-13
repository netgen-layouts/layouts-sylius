<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Doctrine\ORM;

use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface as BaseTaxonRepositoryInterface;

/**
 * @extends \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Core\Model\TaxonInterface>
 */
interface TaxonRepositoryInterface extends BaseTaxonRepositoryInterface
{
    public function countTaxonChildren(TaxonInterface $parent): int;
}
