<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle;

use Netgen\Bundle\LayoutsSyliusBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NetgenLayoutsSyliusBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompilerPass\ComponentPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
    }
}
