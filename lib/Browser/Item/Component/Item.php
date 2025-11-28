<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Browser\Item\Component;

use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface as APIComponentInterface;

final class Item implements ItemInterface, ComponentInterface
{
    public string $value {
        get => (string) ComponentId::fromComponent($this->component);
    }

    public string $name {
        get => $this->component->getName();
    }

    public bool $isVisible {
        get => $this->component->isEnabled();
    }

    public bool $isSelectable {
        get => $this->component->isEnabled();
    }

    public function __construct(
        public private(set) APIComponentInterface $component,
    ) {}
}
