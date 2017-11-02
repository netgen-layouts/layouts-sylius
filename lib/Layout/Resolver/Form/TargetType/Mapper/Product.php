<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Layout\Resolver\Form\TargetType\Mapper;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

final class Product extends Mapper
{
    public function getFormType()
    {
        return ContentBrowserType::class;
    }

    public function getFormOptions()
    {
        return array(
            'item_type' => 'sylius_product',
        );
    }
}
