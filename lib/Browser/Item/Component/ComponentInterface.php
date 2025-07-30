<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Browser\Item\Component;

use Netgen\Layouts\Sylius\Component\ComponentInterface as APIComponentInterface;

interface ComponentInterface
{
    /**
     * Returns the Sylius component.
     */
    public function getComponent(): APIComponentInterface;
}
