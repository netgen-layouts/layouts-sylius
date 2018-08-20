<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Taxon;
use PHPUnit\Framework\TestCase;

final class TaxonTest extends TestCase
{
    /**
     * @covers \Netgen\Layouts\Sylius\Validator\Constraint\Taxon::validatedBy
     */
    public function testValidatedBy(): void
    {
        $constraint = new Taxon();
        self::assertSame('nglayouts_sylius_taxon', $constraint->validatedBy());
    }
}
