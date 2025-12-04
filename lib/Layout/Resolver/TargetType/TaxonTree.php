<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class TaxonTree extends TargetType implements ValueObjectProviderInterface
{
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_taxon_tree';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'numeric'),
            new Constraints\Positive(),
            new SyliusConstraints\Taxon(),
        ];
    }

    /**
     * @return int[]|null
     */
    public function provideValue(Request $request): ?array
    {
        $taxon = $request->attributes->get('nglayouts_sylius_resource');
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        $taxonIds = [];
        do {
            $taxonIds[] = (int) $taxon->getId();
            $taxon = $taxon->getParent();
        } while ($taxon instanceof TaxonInterface);

        return $taxonIds;
    }

    public function getValueObject(mixed $value): ?TaxonInterface
    {
        return $this->taxonRepository->find($value);
    }
}
