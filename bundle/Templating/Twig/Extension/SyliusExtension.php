<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SyliusExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ngbm_sylius_product_name',
                [SyliusRuntime::class, 'getProductName']
            ),
            new TwigFunction(
                'ngbm_sylius_taxon_path',
                [SyliusRuntime::class, 'getTaxonPath']
            ),
        ];
    }
}
