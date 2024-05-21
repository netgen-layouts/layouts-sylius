<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventDispatcher;

use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

use function sprintf;

final class SyliusEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $innerEventDispatcher,
        private SymfonyEventDispatcherInterface $eventDispatcher,
    ) {}

    public function dispatch(string $eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource): ResourceControllerEvent
    {
        $event = $this->innerEventDispatcher->dispatch($eventName, $requestConfiguration, $resource);

        $this->eventDispatcher->dispatch($event, sprintf('nglayouts.sylius.resource.%s', $eventName));

        return $event;
    }

    public function dispatchMultiple(string $eventName, RequestConfiguration $requestConfiguration, $resources): ResourceControllerEvent
    {
        $event = $this->innerEventDispatcher->dispatchMultiple($eventName, $requestConfiguration, $resources);

        $this->eventDispatcher->dispatch($event, sprintf('nglayouts.sylius.resource.%s', $eventName));

        return $event;
    }

    public function dispatchPreEvent(string $eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource): ResourceControllerEvent
    {
        $event = $this->innerEventDispatcher->dispatchPreEvent($eventName, $requestConfiguration, $resource);

        $this->eventDispatcher->dispatch($event, sprintf('nglayouts.sylius.resource.pre_%s', $eventName));

        return $event;
    }

    public function dispatchPostEvent(string $eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource): ResourceControllerEvent
    {
        $event = $this->innerEventDispatcher->dispatchPostEvent($eventName, $requestConfiguration, $resource);

        $this->eventDispatcher->dispatch($event, sprintf('nglayouts.sylius.resource.post_%s', $eventName));

        return $event;
    }

    public function dispatchInitializeEvent(string $eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource): ResourceControllerEvent
    {
        $event = $this->innerEventDispatcher->dispatchInitializeEvent($eventName, $requestConfiguration, $resource);

        $this->eventDispatcher->dispatch($event, sprintf('nglayouts.sylius.resource.initialize_%s', $eventName));

        return $event;
    }
}
