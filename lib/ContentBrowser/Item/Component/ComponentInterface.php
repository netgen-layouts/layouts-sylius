<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\ContentBrowser\Item\Component;

use Netgen\Layouts\Sylius\API\ComponentInterface as APIComponentInterface;

interface ComponentInterface
{
    /**
     * Returns the Sylius component.
     */
    public function getComponent(): APIComponentInterface;
}
