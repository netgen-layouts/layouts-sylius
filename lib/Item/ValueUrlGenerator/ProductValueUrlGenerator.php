<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Item\ValueUrlGenerator;

use Netgen\BlockManager\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductValueUrlGenerator implements ValueUrlGeneratorInterface
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generate($object): ?string
    {
        return $this->urlGenerator->generate(
            'sylius_shop_product_show',
            [
                'slug' => $object->getSlug(),
            ]
        );
    }
}
