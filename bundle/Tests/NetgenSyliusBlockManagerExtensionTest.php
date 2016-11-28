<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\DependencyInjection;

use Netgen\Bundle\BlockManagerBundle\DependencyInjection\NetgenBlockManagerExtension;
use Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class NetgenSyliusBlockManagerExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Return an array of container extensions that need to be registered for
     * each test (usually just the container extension you are testing).
     *
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return array(new NetgenSyliusBlockManagerExtension());
    }

    /**
     * We test for existence of one service from each of the config files.
     *
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension::load
     */
    public function testServices()
    {
        $this->load();

        $this->assertContainerBuilderHasService('netgen_block_manager.item.value_converter.sylius_product');
    }

    /**
     * We test for existence of one config value from each of the config files.
     *
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\DependencyInjection\NetgenSyliusBlockManagerExtension::prepend
     */
    public function testPrepend()
    {
        $this->container->setParameter('kernel.bundles', array('NetgenBlockManagerBundle' => true));
        $this->container->registerExtension(new NetgenBlockManagerExtension());

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
    }
}
