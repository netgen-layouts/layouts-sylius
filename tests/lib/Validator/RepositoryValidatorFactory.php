<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Validator\ChannelValidator;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Netgen\Layouts\Sylius\Validator\ProductValidator;
use Netgen\Layouts\Sylius\Validator\TaxonValidator;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class RepositoryValidatorFactory implements ConstraintValidatorFactoryInterface
{
    private ConstraintValidatorFactory $baseValidatorFactory;

    /**
     * @param \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Resource\Model\ResourceInterface> $repository
     */
    public function __construct(private RepositoryInterface $repository)
    {
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

        if ($name === 'nglayouts_sylius_locale') {
            return new LocaleValidator($this->repository);
        }

        return $this->baseValidatorFactory->getInstance($constraint);
    }
}
