<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\ItemValue;
use Netgen\Layouts\Sylius\Repository\ComponentRepositoryInterface;
use Netgen\Layouts\Sylius\Validator\Constraint\Component;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_string;

final class ComponentValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ComponentRepositoryInterface $componentRepository,
    ) {
    }

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

        $itemValue = ItemValue::fromValue($value);

        $component = $this->componentRepository->load($itemValue->getComponentTypeIdentifier(), $itemValue->getId());

        if (!$component instanceof ComponentInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%identifier%', $itemValue->getComponentTypeIdentifier())
                ->setParameter('%id%', (string) $itemValue->getId())
                ->addViolation();
        }
    }
}
