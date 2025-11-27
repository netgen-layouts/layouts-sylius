<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder<'array'>
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netgen_layouts_sylius');
        $rootNode = $treeBuilder->getRootNode();

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
                ->arrayNode('page_target')
                    ->children()
                        ->arrayNode('available_pages')
                            ->stringPrototype()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end()
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

        return $treeBuilder;
    }
}
