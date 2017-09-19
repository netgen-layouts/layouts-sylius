<?php

namespace Netgen\BlockManager\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class Taxon extends Constraint
{
    /**
     * @var string
     */
    public $message = 'netgen_block_manager.sylius_taxon.taxon_not_found';

    public function validatedBy()
    {
        return 'ngbm_sylius_taxon';
    }
}
