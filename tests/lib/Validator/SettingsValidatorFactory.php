<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Validator\ResourceTypeValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class SettingsValidatorFactory implements ConstraintValidatorFactoryInterface
{
    private ConstraintValidatorFactory $baseValidatorFactory;

    /**
     * @param array<string, string> $settings
     */
    public function __construct(private array $settings)
    {
        $this->baseValidatorFactory = new ConstraintValidatorFactory();
    }

    public function getInstance(Constraint $constraint): ConstraintValidatorInterface
    {
        return match ($constraint->validatedBy()) {
            'nglayouts_sylius_resource_type' => new ResourceTypeValidator($this->settings),
            default => $this->baseValidatorFactory->getInstance($constraint),
        };
    }
}
