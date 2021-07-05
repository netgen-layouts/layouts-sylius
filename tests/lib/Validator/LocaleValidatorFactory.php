<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Locale\LocaleProviderInterface;
use Netgen\Layouts\Sylius\Validator\LocaleValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class LocaleValidatorFactory implements ConstraintValidatorFactoryInterface
{
    private LocaleProviderInterface $localeProvider;

    private ConstraintValidatorFactory $baseValidatorFactory;

    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
        $this->baseValidatorFactory = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        $name = $constraint->validatedBy();

        if ($name === 'nglayouts_sylius_locale') {
            return new LocaleValidator($this->localeProvider);
        }

        return $this->baseValidatorFactory->getInstance($constraint);
    }
}
