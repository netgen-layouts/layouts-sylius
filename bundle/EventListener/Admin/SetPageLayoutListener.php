<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Admin;

use Netgen\Bundle\LayoutsAdminBundle\Event\AdminMatchEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SetPageLayoutListener implements EventSubscriberInterface
{
    public function __construct(
        private string $pageLayoutTemplate,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [AdminMatchEvent::class => ['onAdminMatch', -255]];
    }

    /**
     * Sets the pagelayout template for admin interface.
     */
    public function onAdminMatch(AdminMatchEvent $event): void
    {
        $pageLayoutTemplate = $event->getPageLayoutTemplate();
        if ($pageLayoutTemplate !== null) {
            return;
        }

        $event->setPageLayoutTemplate($this->pageLayoutTemplate);
    }
}
