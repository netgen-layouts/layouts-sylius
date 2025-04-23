<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ResourceShowListener implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack) {}

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return ['nglayouts.sylius.resource.show' => 'onResourceShow'];
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
            $currentRequest->attributes->set('nglayouts_sylius_component_show_id', $resource->getId());
        }

        if ($resource instanceof ComponentInterface) {
            $currentRequest->attributes->set('nglayouts_sylius_component_show_identifier', $resource->getIdentifier());
        }
    }
}
