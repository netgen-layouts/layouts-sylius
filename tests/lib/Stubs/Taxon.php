<?php

namespace Netgen\BlockManager\Sylius\Tests\Stubs;

use Sylius\Component\Core\Model\Taxon as BaseTaxon;

class Taxon extends BaseTaxon
{
    /**
     * @param int $id
     */
    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
    }
}
