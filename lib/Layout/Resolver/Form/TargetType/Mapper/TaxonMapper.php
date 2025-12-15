<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserIntegerType;
use Netgen\Layouts\Layout\Resolver\Form\TargetType\Mapper;

final class TaxonMapper extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserIntegerType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'item_type' => 'sylius_taxon',
        ];
    }
}
