<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\TargetType;

use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\BlockManager\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Taxon implements TargetTypeInterface
{
    public function getType()
    {
        return 'sylius_taxon';
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
        $taxon = $request->attributes->get('ngbm_sylius_taxon');
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        $taxonIds = array();
        do {
            $taxonIds[] = $taxon->getId();
            $taxon = $taxon->getParent();
        } while ($taxon instanceof TaxonInterface);

        return $taxonIds;
    }
}
