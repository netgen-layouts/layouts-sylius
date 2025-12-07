<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\View\Provider;

use Netgen\Layouts\Exception\View\ViewProviderException;
use Netgen\Layouts\Sylius\View\View\SyliusResourceView;
use Netgen\Layouts\View\Provider\ViewProviderInterface;
use Netgen\Layouts\View\ViewInterface;
use Sylius\Resource\Model\ResourceInterface;

use function array_key_exists;
use function is_string;

/**
 * @implements \Netgen\Layouts\View\Provider\ViewProviderInterface<\Sylius\Resource\Model\ResourceInterface>
 */
final class SyliusResourceViewProvider implements ViewProviderInterface
{
    public function provideView(mixed $value, array $parameters = []): ViewInterface
    {
        if (!array_key_exists('view_type', $parameters)) {
            throw ViewProviderException::noParameter('sylius_resource', 'view_type');
        }

        if (!is_string($parameters['view_type'])) {
            throw ViewProviderException::invalidParameter('sylius_resource', 'view_type', 'string');
        }

        return new SyliusResourceView($value, $parameters['view_type']);
    }

    public function supports(mixed $value): bool
    {
        return $value instanceof ResourceInterface;
    }
}
