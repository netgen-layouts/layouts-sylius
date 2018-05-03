<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Parameters\Form\Mapper;

use Netgen\BlockManager\Parameters\Form\Mapper;
use Netgen\BlockManager\Parameters\ParameterDefinition;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

final class TaxonMapper extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserType::class;
    }

    public function mapOptions(ParameterDefinition $parameterDefinition): array
    {
        return [
            'item_type' => 'sylius_taxon',
            'required' => $parameterDefinition->isRequired(),
        ];
    }
}
