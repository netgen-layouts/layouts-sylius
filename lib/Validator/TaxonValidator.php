<?php

namespace Netgen\BlockManager\Sylius\Validator;

use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TaxonValidator extends ConstraintValidator
{
    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface
     */
    protected $taxonRepository;

    /**
     * Constructor.
     *
     * @param \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(TaxonRepositoryInterface $taxonRepository)
    {
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
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
                ->setParameter('%taxonId%', $value)
                ->addViolation();
        }
    }
}
