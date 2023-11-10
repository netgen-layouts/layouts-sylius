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

        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $this->addResourceTypeConditionConfiguration($rootNode);
        $this->addPageTargetConfiguration($rootNode);

        return $treeBuilder;
    }

    private function addResourceTypeConditionConfiguration(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('resource_type_condition')
                    ->children()
                        ->arrayNode('available_resources')
                            ->prototype('scalar')
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
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
