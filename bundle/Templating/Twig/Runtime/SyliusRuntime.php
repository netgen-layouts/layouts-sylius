<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use function array_reverse;

final class SyliusRuntime
{
    private ProductRepositoryInterface $productRepository;

    private TaxonRepositoryInterface $taxonRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TaxonRepositoryInterface $taxonRepository
    ) {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
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
}
