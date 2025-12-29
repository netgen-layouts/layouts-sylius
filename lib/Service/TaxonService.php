<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Service;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TaxonService extends EntityRepository implements TaxonServiceInterface
{
    public function countTaxonChildren(TaxonInterface $parent): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.parent = :parent')
            ->setParameter('parent', $parent->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
