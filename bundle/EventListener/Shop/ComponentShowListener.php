<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ComponentShowListener implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack) {}

    public static function getSubscribedEvents(): array
    {
        return ['nglayouts.sylius.resource.show' => 'onResourceShow'];
    }

    /**
     * Sets the currently displayed component to the request.
     */
    public function onResourceShow(ResourceControllerEvent $event): void
    {
        $resource = $event->getSubject();
        if (!$resource instanceof ComponentInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        $currentRequest->attributes->set('nglayouts_sylius_component_show_id', $resource->getId());
        $currentRequest->attributes->set('nglayouts_sylius_component_show_identifier', $resource::getIdentifier());
    }
}
