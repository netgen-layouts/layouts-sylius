<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TaxonRepository extends BaseTaxonRepository implements TaxonRepositoryInterface
{
    public function countTaxonChildren(TaxonInterface $parent): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.parent = :parent')
            ->setParameter('parent', $parent->getId())
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
