<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

final class Taxon extends Constraint
{
    /**
     * @var string
     */
    public $message = 'netgen_block_manager.sylius_taxon.taxon_not_found';

    public function validatedBy(): string
    {
        return 'ngbm_sylius_taxon';
    }
}
