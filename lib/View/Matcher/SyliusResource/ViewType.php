<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Matcher\SyliusResource;

use Netgen\Layouts\Sylius\View\View\SyliusResourceViewInterface;
use Netgen\Layouts\View\Matcher\MatcherInterface;
use Netgen\Layouts\View\ViewInterface;

use function in_array;

final class ViewType implements MatcherInterface
{
    public function match(ViewInterface $view, array $config): bool
    {
        if (!$view instanceof SyliusResourceViewInterface) {
            return false;
        }

        return in_array($view->getViewType(), $config, true);
    }
}
