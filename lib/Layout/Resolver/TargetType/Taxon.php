<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

/**
 * @deprecated this class will be renamed to TaxonTree in 2.0 release
 */
final class Taxon extends TargetType
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
}
