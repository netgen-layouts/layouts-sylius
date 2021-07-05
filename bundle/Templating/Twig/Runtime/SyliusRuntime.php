<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use function array_reverse;

final class SyliusRuntime
{
    private ProductRepositoryInterface $productRepository;

    private TaxonRepositoryInterface $taxonRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LocaleProviderInterface $localeProvider;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TaxonRepositoryInterface $taxonRepository,
        ChannelRepositoryInterface $channelRepository,
        LocaleProviderInterface $localeProvider
    ) {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
        $this->channelRepository = $channelRepository;
        $this->localeProvider = $localeProvider;
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
        $locales = $this->localeProvider->getAvailableLocales();
        if (!array_key_exists($locale, $locales)) {
            return null;
        }

        return $locales[$locale];
    }
}
