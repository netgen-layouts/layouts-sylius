<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use function array_map;

final class TaxonProduct extends TargetType implements ValueObjectProviderInterface
{
    /**
     * @param \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface> $taxonRepository
     */
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_taxon_product';
    }

    public function getConstraints(): array
    {
        return [
            new Constraints\NotBlank(),
            new Constraints\Type(type: 'int'),
            new Constraints\Positive(),
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

    public function getValueObject(int|string $value): ?TaxonInterface
    {
        return $this->taxonRepository->find((int) $value);
    }
}
