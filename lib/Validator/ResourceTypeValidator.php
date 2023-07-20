<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\ResourceType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function in_array;
use function is_string;

final class ResourceTypeValidator extends ConstraintValidator
{
    /**
     * @param array<string, string> $allowedResources
     */
    public function __construct(private readonly array $allowedResources)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof ResourceType) {
            throw new UnexpectedTypeException($constraint, ResourceType::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!in_array($value, $this->allowedResources, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%resource_type%', $value)
                ->addViolation();
        }
    }
}
