<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netgen_layouts_sylius');

        $rootNode = $treeBuilder->getRootNode();
        $this->addResourceTypeConditionConfiguration($rootNode);
        $this->addPageTargetConfiguration($rootNode);
        $this->addComponentRoutesConfiguration($rootNode);

        return $treeBuilder;
    }

    private function addResourceTypeConditionConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('resource_type_condition')
                    ->children()
                        ->arrayNode('available_resources')
                            ->stringPrototype()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addPageTargetConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('page_target')
                    ->children()
                        ->arrayNode('available_pages')
                            ->stringPrototype()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addComponentRoutesConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('component_routes')
                    ->children()
                        ->arrayNode('create')
                            ->stringPrototype()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                        ->arrayNode('update')
                            ->stringPrototype()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
