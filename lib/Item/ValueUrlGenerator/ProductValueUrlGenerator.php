<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueUrlGenerator;

use Netgen\Layouts\Item\ExtendedValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @implements \Netgen\Layouts\Item\ExtendedValueUrlGeneratorInterface<\Sylius\Component\Product\Model\ProductInterface>
 */
final class ProductValueUrlGenerator implements ExtendedValueUrlGeneratorInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generateDefaultUrl(object $object): ?string
    {
        return $this->urlGenerator->generate(
            'sylius_shop_product_show',
            [
                'slug' => $object->getSlug(),
            ],
        );
    }

    public function generateAdminUrl(object $object): ?string
    {
        return $this->urlGenerator->generate(
            'sylius_admin_product_show',
            [
                'id' => $object->getId(),
            ],
        );
    }

    public function generate(object $object): ?string
    {
        return $this->generateDefaultUrl($object);
    }
}
