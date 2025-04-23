<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Sylius\API\ComponentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ResourceIndexListener implements EventSubscriberInterface
{
    public function __construct
    (
        private readonly RequestStack $requestStack,
    ) {}

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return ['sylius.resource.index' => 'onResourceIndex'];
    }

    public function onResourceIndex(ResourceControllerEvent $event): void
    {
        $subject = $event->getSubject();

        if (!$subject instanceof ResourceGridView) {
            return;
        }

        $firstObject = $subject->getData()?->getCurrentPageResults()[0] ?? null;

        if (!$firstObject instanceof ComponentInterface) {
            return;
        }

        $componentIdentifier = $firstObject?->getIdentifier() ?? null;
        $componentId = $subject?->getRequestConfiguration()?->getRequest()?->query?->get('id') ?? null;

        if ($componentIdentifier === null || $componentId === null) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('nglayouts_sylius_component_index_selected_id', $componentId);
            $currentRequest->attributes->set('nglayouts_sylius_component_index_identifier', $componentIdentifier);
        }
    }
}
