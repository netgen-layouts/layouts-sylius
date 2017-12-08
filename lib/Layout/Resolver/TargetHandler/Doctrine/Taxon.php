<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Netgen\BlockManager\Layout\Resolver\TargetHandler\Doctrine\TargetHandlerInterface;

final class Taxon implements TargetHandlerInterface
{
    public function handleQuery(QueryBuilder $query, $value)
    {
        $query->andWhere(
            $query->expr()->in('rt.value', array(':target_value'))
        )
        ->setParameter('target_value', $value, Connection::PARAM_INT_ARRAY);
    }
}
