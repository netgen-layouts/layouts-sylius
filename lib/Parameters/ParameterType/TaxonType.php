<?php

namespace Netgen\BlockManager\Sylius\Parameters\ParameterType;

use Netgen\BlockManager\Parameters\ParameterInterface;
use Netgen\BlockManager\Parameters\ParameterType;
use Netgen\BlockManager\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a taxon in Sylius.
 */
final class TaxonType extends ParameterType
{
    public function getIdentifier()
    {
        return 'sylius_taxon';
    }

    public function isValueEmpty(ParameterInterface $parameter, $value)
    {
        return $value === null;
    }

    protected function getValueConstraints(ParameterInterface $parameter, $value)
    {
        return array(
            new Constraints\Type(array('type' => 'numeric')),
            new Constraints\GreaterThan(array('value' => 0)),
            new SyliusConstraints\Taxon(),
        );
    }
}
