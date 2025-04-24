<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Matcher\SyliusResource;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\View\View\SyliusResourceViewInterface;
use Netgen\Layouts\View\Matcher\MatcherInterface;
use Netgen\Layouts\View\ViewInterface;

class ComponentIdentifier implements MatcherInterface
{
    public function match(ViewInterface $view, array $config): bool
    {
        if (!$view instanceof SyliusResourceViewInterface) {
            return false;
        }

        $resource = $view->getResource();
        if (!$resource instanceof ComponentInterface) {
            return false;
        }

        return in_array($resource->getIdentifier(), $config);
    }
}
