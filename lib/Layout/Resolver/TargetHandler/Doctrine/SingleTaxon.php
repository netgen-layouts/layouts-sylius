<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetHandler\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Netgen\Layouts\Persistence\Doctrine\QueryHandler\TargetHandlerInterface;

/**
 * @deprecated this class will be renamed to simply Taxon in next major release
 */
final class SingleTaxon implements TargetHandlerInterface
{
    public function handleQuery(QueryBuilder $query, mixed $value): void
    {
        $query->andWhere(
            $query->expr()->in('rt.value', [':target_value']),
        )
        ->setParameter('target_value', $value, Types::INTEGER);
    }
}
