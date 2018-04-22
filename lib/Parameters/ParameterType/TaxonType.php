<?php

namespace Netgen\BlockManager\Sylius\Parameters\ParameterType;

use Netgen\BlockManager\Parameters\ParameterDefinition;
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

    public function isValueEmpty(ParameterDefinition $parameterDefinition, $value)
    {
        return $value === null;
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, $value)
    {
        return [
            new Constraints\Type(['type' => 'numeric']),
            new Constraints\GreaterThan(['value' => 0]),
            new SyliusConstraints\Taxon(),
        ];
    }
}
