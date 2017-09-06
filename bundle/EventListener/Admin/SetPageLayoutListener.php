<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin;

use Netgen\Bundle\BlockManagerAdminBundle\Event\AdminMatchEvent;
use Netgen\Bundle\BlockManagerAdminBundle\Event\BlockManagerAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetPageLayoutListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $pageLayoutTemplate;

    /**
     * Constructor.
     *
     * @param string $pageLayoutTemplate
     */
    public function __construct($pageLayoutTemplate)
    {
        $this->pageLayoutTemplate = $pageLayoutTemplate;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(BlockManagerAdminEvents::ADMIN_MATCH => array('onAdminMatch', -255));
    }

    /**
     * Sets the pagelayout template for admin interface.
     *
     * @param \Netgen\Bundle\BlockManagerAdminBundle\Event\AdminMatchEvent $event
     */
    public function onAdminMatch(AdminMatchEvent $event)
    {
        $pageLayoutTemplate = $event->getPageLayoutTemplate();
        if ($pageLayoutTemplate !== null) {
            return;
        }

        $event->setPageLayoutTemplate($this->pageLayoutTemplate);
    }
}