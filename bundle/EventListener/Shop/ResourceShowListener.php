<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ResourceShowListener implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['sylius.resource.show' => 'onResourceShow'];
    }

    /**
     * Sets the currently displayed Sylius resource to the request,
     * to be able to match with layout resolver.
     */
    public function onResourceShow(ResourceControllerEvent $event): void
    {
        $resource = $event->getSubject();
        if (!$resource instanceof ResourceInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('nglayouts_sylius_resource', $resource);
        }
    }
}
