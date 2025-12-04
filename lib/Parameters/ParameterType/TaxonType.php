<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\ParameterType;

use Netgen\Layouts\Parameters\ParameterDefinition;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Parameters\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Parameter type used to store and validate an ID of a taxon in Sylius, if needed value object can also be retrieved.
 */
final class TaxonType extends ParameterType implements ValueObjectProviderInterface
{
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    public static function getIdentifier(): string
    {
        return 'sylius_taxon';
    }

    public function getValueObject(mixed $value): ?TaxonInterface
    {
        return $this->taxonRepository->find($value);
    }

    protected function getValueConstraints(ParameterDefinition $parameterDefinition, mixed $value): array
    {
        return [
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusConstraints\Taxon(),
        ];
    }
}
