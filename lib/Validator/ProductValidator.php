<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\Product;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function is_scalar;

final class ProductValidator extends ConstraintValidator
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Product) {
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if (!is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $product = $this->productRepository->find($value);
        if (!$product instanceof ProductInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%productId%', (string) $value)
                ->addViolation();
        }
    }
}
