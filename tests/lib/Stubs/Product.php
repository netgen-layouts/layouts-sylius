<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Stubs;

use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct
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
