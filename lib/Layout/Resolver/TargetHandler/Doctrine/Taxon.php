<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;

final class Taxon implements TargetHandlerInterface
{
    public function handleQuery(QueryBuilder $query, mixed $value): void
    {
        $query->andWhere(
            $query->expr()->in('rt.value', [':target_value']),
        )
        ->setParameter('target_value', $value, Connection::PARAM_INT_ARRAY);
    }
}
