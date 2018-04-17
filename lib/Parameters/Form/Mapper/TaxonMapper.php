<?php

namespace Netgen\BlockManager\Sylius\Parameters\Form\Mapper;

use Netgen\BlockManager\Parameters\Form\Mapper;
use Netgen\BlockManager\Parameters\ParameterDefinitionInterface;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

final class TaxonMapper extends Mapper
{
    public function getFormType()
    {
        return ContentBrowserType::class;
    }

    public function mapOptions(ParameterDefinitionInterface $parameterDefinition)
    {
        return [
            'item_type' => 'sylius_taxon',
            'required' => $parameterDefinition->isRequired(),
        ];
    }
}
