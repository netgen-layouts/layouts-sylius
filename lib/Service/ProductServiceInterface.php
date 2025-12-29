<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Service;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

interface ProductServiceInterface
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
