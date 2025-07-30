<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Block\BlockDefinition\Handler;

use Netgen\Layouts\Block\BlockDefinition\BlockDefinitionHandler;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType\ComponentType;

final class ComponentHandler extends BlockDefinitionHandler
{
    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'component_type',
            ParameterType\HiddenType::class,
            [
                'required' => true,
                'readonly' => true,
            ],
        );

        $builder->add(
            'content',
            ComponentType::class,
            [
                'label' => 'block.sylius_component.content',
                'allow_invalid' => true,
            ],
        );
    }
}
