<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator;

use Netgen\BlockManager\Sylius\Validator\ProductValidator;
use Netgen\BlockManager\Sylius\Validator\TaxonValidator;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class RepositoryValidatorFactory implements ConstraintValidatorFactoryInterface
{
    /**
     * @var \Sylius\Component\Resource\Repository\RepositoryInterface
     */
    private $repository;

    /**
     * @var \Symfony\Component\Validator\ConstraintValidatorFactoryInterface
     */
    private $baseValidatorFactory;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->baseValidatorFactory = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        if ($name === 'ngbm_sylius_product' && $this->repository instanceof ProductRepositoryInterface) {
            return new ProductValidator($this->repository);
        }

        if ($name === 'ngbm_sylius_taxon' && $this->repository instanceof TaxonRepositoryInterface) {
            return new TaxonValidator($this->repository);
        }

        return $this->baseValidatorFactory->getInstance($constraint);
    }
}
