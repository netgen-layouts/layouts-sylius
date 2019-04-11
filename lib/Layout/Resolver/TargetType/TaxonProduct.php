<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetTypeInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class TaxonProduct implements TargetTypeInterface
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

    public function provideValue(Request $request)
    {
        $product = $request->attributes->get('nglayouts_sylius_product');
        if (!$product instanceof ProductInterface) {
            return null;
        }

        return array_map(
            static function (TaxonInterface $taxon) {
                return $taxon->getId();
            },
            $product->getTaxons()->getValues()
        );
    }
}
