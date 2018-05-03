<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\NetgenBlockManagerExtension;
use Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension;

final class NetgenSyliusBlockManagerExtensionTest extends AbstractExtensionTestCase
{
    /**
     * We test for existence of one service from each of the config files.
     *
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension::load
     */
    public function testServices(): void
    {
        $this->load();

        $this->assertContainerBuilderHasService('netgen_block_manager.item.value_converter.sylius_product');
    }

    /**
     * We test for existence of one config value from each of the config files.
     *
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension::prepend
     */
    public function testPrepend(): void
    {
        $this->container->setParameter('kernel.bundles', ['NetgenBlockManagerBundle' => true]);
        $this->container->registerExtension(new NetgenBlockManagerExtension());

        /** @var \Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension $extension */
        $extension = $this->container->getExtension('netgen_sylius_block_manager');
        $extension->prepend($this->container);

        $config = call_user_func_array(
            'array_merge_recursive',
            $this->container->getExtensionConfig('netgen_block_manager')
        );

        $this->assertInternalType('array', $config);

        $this->assertArrayHasKey('item_view', $config['view']);
        $this->assertArrayHasKey('api', $config['view']['item_view']);
        $this->assertArrayHasKey('sylius_product', $config['view']['item_view']['api']);

        $this->assertArrayHasKey('value_types', $config['items']);
        $this->assertArrayHasKey('sylius_product', $config['items']['value_types']);
    }

    protected function getContainerExtensions(): array
    {
        return [new NetgenSyliusBlockManagerExtension()];
    }
}
