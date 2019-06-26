<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Extension;

use Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SyliusExtension extends AbstractExtension
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'nglayouts_sylius_product_name',
                [SyliusRuntime::class, 'getProductName']
            ),
            new TwigFunction(
                'nglayouts_sylius_taxon_path',
                [SyliusRuntime::class, 'getTaxonPath']
            ),
        ];
    }
}
