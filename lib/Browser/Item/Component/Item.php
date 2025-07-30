<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Browser\Item\Component;

use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface as APIComponentInterface;

final class Item implements ItemInterface, ComponentInterface
{
    public function __construct(
        private APIComponentInterface $component,
    ) {}

    public function getValue(): string
    {
        return (string) ComponentId::fromComponent($this->component);
    }

    public function getName(): string
    {
        return $this->component->getName();
    }

    public function isVisible(): bool
    {
        return $this->component->isEnabled();
    }

    public function isSelectable(): bool
    {
        return $this->component->isEnabled();
    }

    public function getComponent(): APIComponentInterface
    {
        return $this->component;
    }
}
