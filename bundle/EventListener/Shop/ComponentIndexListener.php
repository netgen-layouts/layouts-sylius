<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ComponentIndexListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return ['nglayouts.sylius.resource.index' => 'onResourceIndex'];
    }

    public function onResourceIndex(ResourceControllerEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof ResourceGridView) {
            return;
        }

        $component = $subject->getData()?->getCurrentPageResults()[0] ?? null;
        if (!$component instanceof ComponentInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        $componentId = $currentRequest->query->get('id');
        if ($componentId === null) {
            return;
        }

        $currentRequest->attributes->set('nglayouts_sylius_component_index_selected_id', $componentId);
        $currentRequest->attributes->set('nglayouts_sylius_component_index_identifier', $component::getIdentifier());
    }
}
