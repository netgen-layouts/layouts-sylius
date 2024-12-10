<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Item\ValueConverter;

use Netgen\Layouts\Item\ValueConverterInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @implements ValueConverterInterface<TaxonInterface>
 */
final class TaxonValueConverter implements ValueConverterInterface
{
    public function supports(object $object): bool
    {
        return $object instanceof TaxonInterface;
    }

    public function getValueType(object $object): string
    {
        return 'sylius_taxon';
    }

    public function getId(object $object): int
    {
        return $object->getId();
    }

    public function getRemoteId(object $object): int
    {
        return $object->getId();
    }

    public function getName(object $object): string
    {
        return (string) $object->getName();
    }

    public function getIsVisible(object $object): bool
    {
        return $object->isEnabled();
    }

    public function getObject(object $object): TaxonInterface
    {
        return $object;
    }
}
