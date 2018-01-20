<?php

namespace Netgen\BlockManager\Sylius\Tests\Item\Stubs;

use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductInterface;

final class Product extends BaseProduct implements ProductInterface
{
    /**
     * Constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $slug
     */
    public function __construct($id, $name, $slug = null)
    {
        parent::__construct();

        $this->id = $id;

        $this->currentLocale = 'en';
        $this->setName($name);

        $this->setSlug($slug);
    }
}
