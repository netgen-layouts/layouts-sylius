<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Browser\Item\Component;

use Netgen\ContentBrowser\Item\LocationInterface;

final class RootLocation implements LocationInterface
{
    public function getLocationId(): string
    {
        return '';
    }

    public function getName(): string
    {
        return 'All components';
    }

    public function getParentId(): ?int
    {
        return null;
    }
}
