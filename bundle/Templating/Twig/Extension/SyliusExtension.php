<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Runtime\SyliusRuntime;
use Twig_Extension;
use Twig_SimpleFunction;

class SyliusExtension extends Twig_Extension
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
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'ngbm_sylius_product_name',
                array(SyliusRuntime::class, 'getProductName')
            ),
        );
    }
}
