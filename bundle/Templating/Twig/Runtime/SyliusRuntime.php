<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class SyliusRuntime
{
    /**
     * @var \Sylius\Component\Product\Repository\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface
     */
    private $taxonRepository;

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
     *
     * @return string
     */
    public function getProductName($productId)
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
     * @return string
     */
    public function getTaxonPath($taxonId)
    {
        $taxon = $this->taxonRepository->find($taxonId);
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        $parts = array($taxon->getName());
        while ($taxon->getParent() instanceof TaxonInterface) {
            $taxon = $taxon->getParent();

            $parts[] = $taxon->getName();
        }

        return array_reverse($parts);
    }
}
