<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\ContentBrowser\Item\Component;

use Netgen\ContentBrowser\Item\ItemInterface;
use Netgen\Layouts\Sylius\API\ComponentInterface as APIComponentInterface;

final class Item implements ItemInterface, ComponentInterface
{
    public function __construct(
        private readonly APIComponentInterface $component,
    ) {
    }

    public function getValue(): string
    {
        return ItemValue::fromComponent($this->component)->getValue();
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
