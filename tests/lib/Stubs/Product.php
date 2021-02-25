<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Sylius\Component\Core\Model\Product as BaseProduct;

final class Product extends BaseProduct
{
    public function __construct(int $id)
    {
        parent::__construct();

        $this->id = $id;
    }
}
