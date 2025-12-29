<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\TargetType;

use Netgen\Layouts\Layout\Resolver\TargetType;
use Netgen\Layouts\Layout\Resolver\ValueObjectProviderInterface;
use Netgen\Layouts\Sylius\Validator\Constraint as SyliusConstraints;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

final class Taxon extends TargetType implements ValueObjectProviderInterface
{
    /**
     * @param \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface> $taxonRepository
     */
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    public static function getType(): string
    {
        return 'sylius_taxon';
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

    public function provideValue(Request $request): ?int
    {
        $taxon = $request->attributes->get('nglayouts_sylius_resource');
        if (!$taxon instanceof TaxonInterface) {
            return null;
        }

        return $taxon->getId();
    }

    public function getValueObject(int|string $value): ?TaxonInterface
    {
        return $this->taxonRepository->find((int) $value);
    }
}
