<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Browser\Item\Component;

use Netgen\ContentBrowser\Item\LocationInterface;

final class RootLocation implements LocationInterface
{
    public string $locationId {
        get => '';
    }

    public string $name {
        get => 'All components';
    }

    public null $parentId {
        get => null;
    }
}
