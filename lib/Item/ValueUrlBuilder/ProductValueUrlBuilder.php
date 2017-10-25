<?php

namespace Netgen\BlockManager\Sylius\Item\ValueUrlBuilder;

use Netgen\BlockManager\Item\ValueUrlBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductValueUrlBuilder implements ValueUrlBuilderInterface
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getUrl($object)
    {
        return $this->urlGenerator->generate(
            'sylius_shop_product_show',
            array(
                'slug' => $object->getSlug(),
            )
        );
    }
}
