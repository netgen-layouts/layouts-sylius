<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Item\Stubs;

use Sylius\Component\Product\Model\Product as BaseProduct;

final class Product extends BaseProduct
{
    public function __construct(int $id, string $name, ?string $slug = null, bool $enabled = true)
    {
        parent::__construct();

        $this->id = $id;

        $this->currentLocale = 'en';
        $this->setName($name);
        $this->setSlug($slug);
        $this->setEnabled($enabled);
    }
}
