<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\Form\Mapper;

use Netgen\ContentBrowser\Form\Type\ContentBrowserType;
use Netgen\Layouts\Exception\Parameters\ParameterException;
use Netgen\Layouts\Parameters\Form\Mapper;
use Netgen\Layouts\Parameters\ParameterDefinition;

final class ComponentMapper extends Mapper
{
    public function getFormType(): string
    {
        return ContentBrowserType::class;
    }

    public function mapOptions(ParameterDefinition $parameterDefinition): array
    {
        $options = [
            'item_type' => 'sylius_component',
            'block_prefix' => 'ngcb_sylius_component',
            'required' => $parameterDefinition->isRequired(),
        ];

        try {
            $options['custom_params']['component_type_identifier'] = $parameterDefinition->getOption('component_type_identifier') ?? false;
        } catch (ParameterException $e) {
            $options['custom_params']['component_type_identifier'] = false;
        }

        return $options;
    }
}
