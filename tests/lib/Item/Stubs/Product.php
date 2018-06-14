<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Item\Stubs;

use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductInterface;

final class Product extends BaseProduct implements ProductInterface
{
    /**
     * @param int|string $id
     * @param string $name
     * @param string|null $slug
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
