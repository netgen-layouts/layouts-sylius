<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a taxon in Sylius.
 */
final class TaxonType extends ParameterType
{
    public static function getIdentifier(): string
    {
        return 'sylius_taxon';
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, $value): array
    {
        return [
            new Constraints\Type(['type' => 'numeric']),
            new Constraints\GreaterThan(['value' => 0]),
            new SyliusConstraints\Taxon(),
        ];
    }
}
