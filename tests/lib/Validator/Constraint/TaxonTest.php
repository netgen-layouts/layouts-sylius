<?php

namespace Netgen\BlockManager\Sylius\Tests\Validator\Constraint;

use Netgen\BlockManager\Sylius\Validator\Constraint\Taxon;
use PHPUnit\Framework\TestCase;

final class TaxonTest extends TestCase
{
    /**
     * @covers \Netgen\BlockManager\Sylius\Validator\Constraint\Taxon::validatedBy
     */
    public function testValidatedBy()
    {
        $constraint = new Taxon();
        $this->assertEquals('ngbm_sylius_taxon', $constraint->validatedBy());
    }
}
