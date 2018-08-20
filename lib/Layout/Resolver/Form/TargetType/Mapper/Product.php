<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\BlockManager\Layout\Resolver\Form\TargetType\Mapper;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

final class Product extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'item_type' => 'sylius_product',
        ];
    }
}
