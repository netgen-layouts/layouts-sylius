<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SyliusExtension extends AbstractExtension
{
    public function getName()
    {
        return self::class;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'ngbm_sylius_product_name',
                array(SyliusRuntime::class, 'getProductName')
            ),
            new TwigFunction(
                'ngbm_sylius_taxon_path',
                array(SyliusRuntime::class, 'getTaxonPath')
            ),
        );
    }
}
