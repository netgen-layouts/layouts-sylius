<?php

namespace Netgen\BlockManager\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Layout\Resolver\Form\TargetType\Mapper;
use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

class Product extends Mapper
{
    public function getFormType()
    {
        return ContentBrowserType::class;
    }

    public function mapOptions(TargetTypeInterface $targetType)
    {
        return array(
            'item_type' => 'sylius_product',
        );
    }
}
