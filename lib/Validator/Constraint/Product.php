<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Product extends Constraint
{
    public string $message = 'netgen_layouts.sylius.product.product_not_found';

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_product';
    }
}
