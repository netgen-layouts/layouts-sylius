<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\Page;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function in_array;
use function is_string;

final class PageValidator extends ConstraintValidator
{
    /**
     * @param array<string, string> $allowedPages
     */
    public function __construct(private readonly array $allowedPages)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Page) {
            throw new UnexpectedTypeException($constraint, Page::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!in_array($value, $this->allowedPages, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%page%', $value)
                ->addViolation();
        }
    }
}
