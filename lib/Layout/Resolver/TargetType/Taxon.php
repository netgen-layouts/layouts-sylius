<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Taxon implements TargetTypeInterface
{
    public static function getType(): string
    {
        return 'sylius_taxon';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(['type' => 'numeric']),
            new Constraints\GreaterThan(['value' => 0]),
            new SyliusConstraints\Taxon(),
        ];
    }

    public function provideValue(Request $request)
    {
        $taxon = $request->attributes->get('nglayouts_sylius_taxon');
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        $taxonIds = [];
        do {
            $taxonIds[] = $taxon->getId();
            $taxon = $taxon->getParent();
        } while ($taxon instanceof TaxonInterface);

        return $taxonIds;
    }
}
