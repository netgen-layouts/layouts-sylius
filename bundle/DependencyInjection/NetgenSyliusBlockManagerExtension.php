<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

final class NetgenSyliusBlockManagerExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services/items.yml');
        $loader->load('services/security.yml');
        $loader->load('services/event_listeners.yml');
        $loader->load('services/layout_resolver.yml');
        $loader->load('services/validators.yml');
        $loader->load('services/templating.yml');
        $loader->load('services/query_types.yml');
        $loader->load('services/parameters.yml');
        $loader->load('services/locale.yml');
        $loader->load('default_settings.yml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $prependConfigs = [
            'liip_imagine.yml' => 'liip_imagine',
            'design.yml' => 'netgen_block_manager',
            'value_types.yml' => 'netgen_block_manager',
            'query_types.yml' => 'netgen_block_manager',
            'view/item_view.yml' => 'netgen_block_manager',
            'view/rule_target_view.yml' => 'netgen_block_manager',
        ];

        foreach ($prependConfigs as $configFile => $prependConfig) {
            $configFile = __DIR__ . '/../Resources/config/' . $configFile;
            $config = Yaml::parse((string) file_get_contents($configFile));
            $container->prependExtensionConfig($prependConfig, $config);
            $container->addResource(new FileResource($configFile));
        }
    }
}
