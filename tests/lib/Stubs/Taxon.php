<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Sylius\Component\Core\Model\Taxon as BaseTaxon;

final class Taxon extends BaseTaxon
{
    public function __construct(int $id)
    {
        parent::__construct();

        $this->id = $id;
    }
}
