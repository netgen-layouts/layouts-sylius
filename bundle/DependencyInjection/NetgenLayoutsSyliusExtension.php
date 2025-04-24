<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

use function file_get_contents;

final class NetgenLayoutsSyliusExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param mixed[] $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');

        $loader = new DelegatingLoader(
            new LoaderResolver(
                [
                    new GlobFileLoader($container, $locator),
                    new YamlFileLoader($container, $locator),
                ],
            ),
        );

        $loader->load('services/**/*.yaml', 'glob');
        $loader->load('default_parameters.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'netgen_layouts.sylius.condition.resource_types',
            $config['resource_type_condition']['available_resources'],
        );

        $container->setParameter(
            'netgen_layouts.sylius.target.pages',
            $config['page_target']['available_pages'],
        );

        $container->setParameter(
            'netgen_layouts.sylius.component_create_routes',
            $config['component_routes']['create'] ?? [],
        );

        $container->setParameter(
            'netgen_layouts.sylius.component_update_routes',
            $config['component_routes']['update'] ?? [],
        );
    }

    public function prepend(ContainerBuilder $container): void
    {
        $prependConfigs = [
            'default_settings.yaml' => 'netgen_layouts_sylius',
            'liip_imagine.yaml' => 'liip_imagine',
            'design.yaml' => 'netgen_layouts',
            'value_types.yaml' => 'netgen_layouts',
            'query_types.yaml' => 'netgen_layouts',
            'block_definitions.yaml' => 'netgen_layouts',
            'block_types.yaml' => 'netgen_layouts',
            'sylius_ui.yaml' => 'sylius_ui',
            'view/block_view.yaml' => 'netgen_layouts',
            'view/item_view.yaml' => 'netgen_layouts',
            'view/rule_target_view.yaml' => 'netgen_layouts',
            'view/rule_condition_view.yaml' => 'netgen_layouts',
            'doctrine.yaml' => 'doctrine',
            'item_types.yaml' => 'netgen_content_browser',
            'framework/twig.yaml' => 'twig',
        ];

        foreach ($prependConfigs as $configFile => $prependConfig) {
            $configFile = __DIR__ . '/../Resources/config/' . $configFile;
            $config = Yaml::parse((string) file_get_contents($configFile));
            $container->prependExtensionConfig($prependConfig, $config);
            $container->addResource(new FileResource($configFile));
        }
    }
}
