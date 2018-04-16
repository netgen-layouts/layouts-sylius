<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\TargetType;

use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\BlockManager\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class TaxonProduct implements TargetTypeInterface
{
    public function getType()
    {
        return 'sylius_taxon_product';
    }

    public function getConstraints()
    {
        return array(
            new Constraints\NotBlank(),
            new Constraints\Type(array('type' => 'numeric')),
            new Constraints\GreaterThan(array('value' => 0)),
            new SyliusConstraints\Taxon(),
        );
    }

    public function provideValue(Request $request)
    {
        $product = $request->attributes->get('ngbm_sylius_product');
        if (!$product instanceof ProductInterface) {
            return;
        }

        return array_map(
            function (TaxonInterface $taxon) {
                return $taxon->getId();
            },
            $product->getTaxons()->getValues()
        );
    }
}
