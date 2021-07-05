<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Validator\ChannelValidator;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Netgen\Layouts\Sylius\Validator\ProductValidator;
use Netgen\Layouts\Sylius\Validator\TaxonValidator;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class RepositoryValidatorFactory implements ConstraintValidatorFactoryInterface
{
    private RepositoryInterface $repository;

    private ConstraintValidatorFactory $baseValidatorFactory;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->baseValidatorFactory = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        if ($name === 'nglayouts_sylius_product' && $this->repository instanceof ProductRepositoryInterface) {
            return new ProductValidator($this->repository);
        }

        if ($name === 'nglayouts_sylius_taxon' && $this->repository instanceof TaxonRepositoryInterface) {
            return new TaxonValidator($this->repository);
        }

        if ($name === 'nglayouts_sylius_channel' && $this->repository instanceof ChannelRepositoryInterface) {
            return new ChannelValidator($this->repository);
        }

        if ($name === 'nglayouts_sylius_locale' && $this->repository instanceof RepositoryInterface) {
            return new LocaleValidator($this->repository);
        }

        return $this->baseValidatorFactory->getInstance($constraint);
    }
}
