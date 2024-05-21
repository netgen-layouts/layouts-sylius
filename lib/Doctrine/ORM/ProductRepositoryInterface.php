<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Doctrine\ORM;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface as BaseProductRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @template T of \Sylius\Component\Core\Model\ProductInterface
 *
 * @extends \Sylius\Component\Core\Repository\ProductRepositoryInterface<T>
 */
interface ProductRepositoryInterface extends BaseProductRepositoryInterface
{
    /**
     * @param array<string, string> $sorting
     *
     * @return \Sylius\Component\Product\Model\ProductInterface[]
     */
    public function findByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale, int $offset, int $count, array $sorting = []): array;

    public function countByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale): int;

    /**
     * @return \Sylius\Component\Product\Model\ProductInterface[]
     */
    public function findLatestByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale, int $offset, int $count): array;

    public function countLatestByChannelAndTaxon(ChannelInterface $channel, ?TaxonInterface $taxon, string $locale): int;
}
