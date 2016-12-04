<?php

namespace Netgen\BlockManager\Sylius\Item\ValueUrlBuilder;

use Netgen\BlockManager\Item\ValueUrlBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class ProductValueUrlBuilder implements ValueUrlBuilderInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Returns the value type for which this URL builder builds the URL.
     *
     * @return string
     */
    public function getValueType()
    {
        return 'sylius_product';
    }

    /**
     * Returns the object URL. Take note that this is not a slug,
     * but a full path, i.e. starting with /.
     *
     * @param mixed $object
     *
     * @return string
     */
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
