<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

use function array_reverse;

final class SyliusRuntime
{
    /**
     * @param \Sylius\Component\Resource\Repository\RepositoryInterface<\Sylius\Component\Locale\Model\LocaleInterface> $localeRepository
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private TaxonRepositoryInterface $taxonRepository,
        private ChannelRepositoryInterface $channelRepository,
        private RepositoryInterface $localeRepository,
    ) {}

    /**
     * Returns the product name.
     */
    public function getProductName(int|string $productId): ?string
    {
        $product = $this->productRepository->find($productId);
        if (!$product instanceof ProductInterface) {
            return null;
        }

        return $product->getName();
    }

    /**
     * Returns the taxon path.
     *
     * @return array<string|null>|null
     */
    public function getTaxonPath(int|string $taxonId): ?array
    {
        $taxon = $this->taxonRepository->find($taxonId);
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        $parts = [$taxon->getName()];

        $parentTaxon = $taxon->getParent();
        while ($parentTaxon instanceof TaxonInterface) {
            $parts[] = $parentTaxon->getName();
            $parentTaxon = $parentTaxon->getParent();
        }

        return array_reverse($parts);
    }

    /**
     * Returns the channel name.
     */
    public function getChannelName(int|string $channelId): ?string
    {
        $channel = $this->channelRepository->find($channelId);
        if (!$channel instanceof ChannelInterface) {
            return null;
        }

        return $channel->getName();
    }

    /**
     * Returns the locale name.
     */
    public function getLocaleName(string $code): ?string
    {
        $locale = $this->localeRepository->findOneBy(['code' => $code]);
        if (!$locale instanceof LocaleInterface) {
            return null;
        }

        return $locale->getName();
    }
}
