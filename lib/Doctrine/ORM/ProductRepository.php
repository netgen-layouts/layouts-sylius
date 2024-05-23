<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class ProductRepository extends BaseProductRepository implements ProductRepositoryInterface
{
    public function findByChannelAndTaxon(
        ChannelInterface $channel,
        ?TaxonInterface $taxon,
        string $locale,
        int $offset,
        int $count,
        array $sorting = [],
    ): array {
        if (!$taxon instanceof TaxonInterface) {
            return [];
        }

        $queryBuilder = $this->createByTaxonQueryBuilder($channel, $taxon, $locale, $sorting);

        $queryBuilder
            ->addGroupBy('o.id')
            ->setFirstResult($offset)
            ->setMaxResults($count);

        if (isset($sorting['name'])) {
            $queryBuilder->addOrderBy('translation.name', $sorting['name']);
        }

        if (isset($sorting['position'])) {
            $queryBuilder->addOrderBy('productTaxon.position', $sorting['position']);
        }

        if (isset($sorting['createdAt'])) {
            $queryBuilder->addOrderBy('o.createdAt', $sorting['createdAt']);
        }

        $this->applySorting($queryBuilder, $sorting);

        return $queryBuilder->getQuery()->getResult();
    }

    public function countByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale): int
    {
        if (!$taxon instanceof TaxonInterface) {
            return 0;
        }

        $queryBuilder = $this->createByTaxonQueryBuilder($channel, $taxon, $locale);

        return (int) $queryBuilder
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLatestByChannelAndTaxon(
        ChannelInterface $channel,
        ?TaxonInterface $taxon,
        string $locale,
        int $offset,
        int $count,
    ): array {
        $queryBuilder = $this->createByTaxonQueryBuilder($channel, $taxon, $locale);

        return $queryBuilder
            ->addGroupBy('o.id')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function countLatestByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale): int
    {
        $queryBuilder = $this->createByTaxonQueryBuilder($channel, $taxon, $locale);

        return (int) $queryBuilder
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param array<string, string> $sorting
     */
    public function createByTaxonQueryBuilder(
        ChannelInterface $channel,
        ?TaxonInterface $taxon,
        string $locale,
        array $sorting = [],
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('locale', $locale)
            ->setParameter('channel', $channel->getId())
        ;

        if ($taxon instanceof TaxonInterface) {
            $queryBuilder
                ->innerJoin('o.productTaxons', 'productTaxon')
                ->andWhere('productTaxon.taxon = :taxon')
                ->setParameter('taxon', $taxon->getId());
        }

        // Grid hack, we do not need to join these if we don't sort by price
        if (isset($sorting['price'])) {
            $queryBuilder
                ->innerJoin('o.variants', 'variant')
                ->innerJoin('variant.channelPricings', 'channelPricing')
                ->andWhere('channelPricing.channelCode = :channelCode')
                ->setParameter('channelCode', $channel->getCode())
            ;
        }

        return $queryBuilder;
    }
}
