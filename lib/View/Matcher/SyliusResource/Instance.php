<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Matcher\SyliusResource;

use Netgen\Layouts\Sylius\View\View\SyliusResourceViewInterface;
use Netgen\Layouts\View\Matcher\MatcherInterface;
use Netgen\Layouts\View\ViewInterface;

class Instance implements MatcherInterface
{
    public function match(ViewInterface $view, array $config): bool
    {
        if (!$view instanceof SyliusResourceViewInterface) {
            return false;
        }

        foreach ($config as $fqcn) {
            if ($view->getResource() instanceof $fqcn) {
                return true;
            }
        }

        return false;
    }
}
