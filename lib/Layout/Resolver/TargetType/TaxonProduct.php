<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_map;

final class TaxonProduct extends TargetType
{
    public static function getType(): string
    {
        return 'sylius_taxon_product';
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
        $product = $request->attributes->get('nglayouts_sylius_resource');
        if (!$product instanceof ProductInterface) {
            return null;
        }

        return array_map(
            static fn (TaxonInterface $taxon): int => $taxon->getId(),
            $product->getTaxons()->getValues(),
        );
    }
}
