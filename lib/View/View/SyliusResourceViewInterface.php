<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\View;

use Netgen\Layouts\View\ViewInterface;
use Sylius\Resource\Model\ResourceInterface;

interface SyliusResourceViewInterface extends ViewInterface
{
    /**
     * Returns the Sylius resource.
     */
    public function getResource(): ResourceInterface;

    /**
     * Returns the view type with which the Sylius resource will be rendered.
     */
    public function getViewType(): string;
}
