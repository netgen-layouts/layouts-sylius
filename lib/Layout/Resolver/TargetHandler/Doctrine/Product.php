<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;

final class Product implements TargetHandlerInterface
{
    public function handleQuery(QueryBuilder $query, mixed $value): void
    {
        $query->andWhere(
            $query->expr()->eq('rt.value', ':target_value'),
        )
        ->setParameter('target_value', $value, Types::INTEGER);
    }
}
