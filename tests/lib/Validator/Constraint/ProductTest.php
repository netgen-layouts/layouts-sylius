<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Product;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    /**
     * @covers \Netgen\Layouts\Sylius\Validator\Constraint\Product::validatedBy
     */
    public function testValidatedBy(): void
    {
        $constraint = new Product();
        self::assertSame('nglayouts_sylius_product', $constraint->validatedBy());
    }
}
