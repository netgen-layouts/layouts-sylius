<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SyliusExtension extends AbstractExtension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::class;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return \Twig\TwigFunction[]
     */
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
