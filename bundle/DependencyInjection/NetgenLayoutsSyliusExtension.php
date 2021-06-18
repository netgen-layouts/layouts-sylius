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
        $loader->load('default_settings.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $prependConfigs = [
            'liip_imagine.yaml' => 'liip_imagine',
            'design.yaml' => 'netgen_layouts',
            'value_types.yaml' => 'netgen_layouts',
            'query_types.yaml' => 'netgen_layouts',
            'sylius_ui.yaml' => 'sylius_ui',
            'view/item_view.yaml' => 'netgen_layouts',
            'view/rule_target_view.yaml' => 'netgen_layouts',
            'view/rule_condition_view.yaml' => 'netgen_layouts',
        ];

        foreach ($prependConfigs as $configFile => $prependConfig) {
            $configFile = __DIR__ . '/../Resources/config/' . $configFile;
            $config = Yaml::parse((string) file_get_contents($configFile));
            $container->prependExtensionConfig($prependConfig, $config);
            $container->addResource(new FileResource($configFile));
        }
    }
}
