<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\Stubs;

use Sylius\Component\Product\Model\Product as BaseProduct;

final class Product extends BaseProduct
{
    /**
     * @param int|string $id
     */
    public function __construct($id, string $name, ?string $slug = null)
    {
        parent::__construct();

        $this->id = $id;

        $this->currentLocale = 'en';
        $this->setName($name);

        $this->setSlug($slug);
    }
}
