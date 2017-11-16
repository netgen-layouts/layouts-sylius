<?php

namespace Netgen\BlockManager\Sylius\Parameters\Form\Mapper;

use Netgen\BlockManager\Parameters\Form\Mapper;
use Netgen\BlockManager\Parameters\ParameterInterface;
use Netgen\ContentBrowser\Form\Type\ContentBrowserType;

final class TaxonMapper extends Mapper
{
    public function getFormType()
    {
        return ContentBrowserType::class;
    }

    public function mapOptions(ParameterInterface $parameter)
    {
        return array(
            'item_type' => 'sylius_taxon',
            'required' => $parameter->isRequired(),
        );
    }
}
