<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Validator\Constraint;

use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use PHPUnit\Framework\TestCase;

final class TaxonTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\Constraint\Taxon::validatedBy
     */
    public function testValidatedBy(): void
    {
        $constraint = new Taxon();
        $this->assertEquals('ngbm_sylius_taxon', $constraint->validatedBy());
    }
}
