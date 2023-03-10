<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Taxon::class)]
final class TaxonTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new Taxon();
        self::assertSame('nglayouts_sylius_taxon', $constraint->validatedBy());
    }
}
