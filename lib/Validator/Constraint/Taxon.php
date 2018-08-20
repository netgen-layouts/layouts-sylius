<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Taxon extends Constraint
{
    /**
     * @var string
     */
    public $message = 'netgen_layouts.sylius_taxon.taxon_not_found';

    public function validatedBy(): string
    {
        return 'nglayouts_sylius_taxon';
    }
}
