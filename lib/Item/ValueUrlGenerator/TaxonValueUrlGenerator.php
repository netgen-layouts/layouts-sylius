<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueUrlGenerator;

use Netgen\Layouts\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @implements \Netgen\Layouts\Item\ValueUrlGeneratorInterface<\Sylius\Component\Taxonomy\Model\TaxonInterface>
 */
final class TaxonValueUrlGenerator implements ValueUrlGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    public function generateDefaultUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'sylius_shop_product_index',
            [
                'slug' => $object->getSlug(),
            ],
        );
    }

    public function generateAdminUrl(object $object): string
    {
        return $this->urlGenerator->generate(
            'sylius_admin_taxon_update',
            [
                'id' => $object->getId(),
            ],
        );
    }
}
