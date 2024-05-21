<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\Taxon;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_scalar;

final class TaxonValidator extends ConstraintValidator
{
    /**
     * @param \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface<\Sylius\Component\Core\Model\TaxonInterface> $taxonRepository
     */
    public function __construct(private TaxonRepositoryInterface $taxonRepository) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Taxon) {
            throw new UnexpectedTypeException($constraint, Taxon::class);
        }

        if (!is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $taxon = $this->taxonRepository->find($value);
        if (!$taxon instanceof TaxonInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%taxonId%', (string) $value)
                ->addViolation();
        }
    }
}
