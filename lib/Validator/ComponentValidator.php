<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint\Component;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_string;

final class ComponentValidator extends ConstraintValidator
{
    public function __construct(
        private ComponentRepositoryInterface $componentRepository,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Component) {
            throw new UnexpectedTypeException($constraint, Component::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $componentId = ComponentId::fromString($value);

        $component = $this->componentRepository->load($componentId);

        if (!$component instanceof ComponentInterface) {
            if (!$constraint->allowInvalid) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%type%', $componentId->getComponentType())
                    ->setParameter('%id%', (string) $componentId->getId())
                    ->addViolation();
            }
        }
    }
}
