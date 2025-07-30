<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ComponentInitializeCreateListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return ['nglayouts.sylius.resource.initialize_create' => 'onResourceInitializeCreate'];
    }

    public function onResourceInitializeCreate(ResourceControllerEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof ComponentInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        $currentRequest->attributes->set('nglayouts_sylius_component_initialize_create_identifier', $subject::getIdentifier());
    }
}
