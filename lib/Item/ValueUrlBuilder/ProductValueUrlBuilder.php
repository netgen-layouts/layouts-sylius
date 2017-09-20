<?php

namespace Netgen\BlockManager\Sylius\Item\ValueUrlBuilder;

use Netgen\BlockManager\Item\ValueUrlBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductValueUrlBuilder implements ValueUrlBuilderInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getUrl($object)
    {
        return $this->router->generate(
            'sylius_shop_product_show',
            array(
                'slug' => $object->getSlug(),
            )
        );
    }
}
