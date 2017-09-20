<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Admin;

use Netgen\Bundle\BlockManagerAdminBundle\Event\AdminMatchEvent;
use Netgen\Bundle\BlockManagerAdminBundle\Event\BlockManagerAdminEvents;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class SetPageLayoutListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener
     */
    private $listener;

    public function setUp()
    {
        $this->listener = new SetPageLayoutListener('pagelayout.html.twig');
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array(BlockManagerAdminEvents::ADMIN_MATCH => array('onAdminMatch', -255)),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::onAdminMatch
     */
    public function testOnAdminMatch()
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onAdminMatch($event);

        $this->assertEquals('pagelayout.html.twig', $event->getPageLayoutTemplate());
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::onAdminMatch
     */
    public function testOnAdminMatchWithExistingPageLayout()
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MASTER_REQUEST);
        $event->setPageLayoutTemplate('existing.html.twig');

        $this->listener->onAdminMatch($event);

        $this->assertEquals('existing.html.twig', $event->getPageLayoutTemplate());
    }
}
