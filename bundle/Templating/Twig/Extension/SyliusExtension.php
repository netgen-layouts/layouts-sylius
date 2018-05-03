<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SyliusExtension extends AbstractExtension
{
    public function getFunctions(): array
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
