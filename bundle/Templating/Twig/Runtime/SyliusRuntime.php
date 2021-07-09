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
    private ProductRepositoryInterface $productRepository;

    private TaxonRepositoryInterface $taxonRepository;

    private ChannelRepositoryInterface $channelRepository;

    private RepositoryInterface $localeRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TaxonRepositoryInterface $taxonRepository,
        ChannelRepositoryInterface $channelRepository,
        RepositoryInterface $localeRepository
    ) {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
        $this->channelRepository = $channelRepository;
        $this->localeRepository = $localeRepository;
    }

    /**
     * Returns the product name.
     *
     * @param int|string $productId
     */
    public function getProductName($productId): ?string
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
     * @param int|string $taxonId
     *
     * @return array<string|null>|null
     */
    public function getTaxonPath($taxonId): ?array
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
     *
     * @param int|string $channelId
     */
    public function getChannelName($channelId): ?string
    {
        $channel = $this->channelRepository->find($channelId);
        if (!$channel instanceof ChannelInterface) {
            return null;
        }

        return $channel->getName();
    }

    /**
     * Returns the locale name.
     *
     * @param string $locale
     */
    public function getLocaleName(string $locale): ?string
    {
        $locale = $this->localeRepository->findOneBy(['code' => $locale]);
        if (!$locale instanceof LocaleInterface) {
            return null;
        }

        return $locale->getName();
    }
}
