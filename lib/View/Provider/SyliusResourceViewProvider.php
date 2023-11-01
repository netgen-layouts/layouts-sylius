<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Provider;

use Netgen\Layouts\Exception\View\ViewProviderException;
use Netgen\Layouts\Sylius\View\View\SyliusResourceView;
use Netgen\Layouts\View\Provider\ViewProviderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Netgen\Layouts\View\ViewInterface;

class SyliusResourceViewProvider implements ViewProviderInterface
{
    public function provideView($value, array $parameters = []): ViewInterface
    {
        if (!isset($parameters['view_type'])) {
            throw ViewProviderException::noParameter('sylius_resource', 'view_type');
        }

        if (!is_string($parameters['view_type'])) {
            throw ViewProviderException::invalidParameter('sylius_resource', 'view_type', 'string');
        }

        return new SyliusResourceView($value, $parameters['view_type']);
    }

    public function supports($value): bool
    {
        return $value instanceof ResourceInterface;
    }
}
