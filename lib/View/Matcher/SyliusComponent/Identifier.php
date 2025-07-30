<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Matcher\SyliusComponent;

use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Netgen\Layouts\Sylius\View\View\SyliusResourceViewInterface;
use Netgen\Layouts\View\Matcher\MatcherInterface;
use Netgen\Layouts\View\ViewInterface;

use function in_array;

final class Identifier implements MatcherInterface
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

        return in_array($resource::getIdentifier(), $config, true);
    }
}
