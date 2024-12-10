<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Layout\Resolver\Form\TargetType\Mapper;

/**
 * @deprecated this class will be renamed to simply Taxon in 2.0 release
 */
final class SingleTaxon extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'item_type' => 'sylius_taxon',
        ];
    }
}
