<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Netgen\BlockManager\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;

final class TaxonProduct implements TargetHandlerInterface
{
    public function handleQuery(QueryBuilder $query, $value)
    {
        $query->andWhere(
            $query->expr()->in('rt.value', [':target_value'])
        )
        ->setParameter('target_value', $value, Connection::PARAM_INT_ARRAY);
    }
}
