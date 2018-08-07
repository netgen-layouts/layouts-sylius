<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Admin;

use Netgen\Bundle\BlockManagerAdminBundle\Event\AdminMatchEvent;
use Netgen\Bundle\BlockManagerAdminBundle\Event\BlockManagerAdminEvents;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class SetPageLayoutListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener
     */
    private $listener;

    public function setUp(): void
    {
        $this->listener = new SetPageLayoutListener('pagelayout.html.twig');
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [BlockManagerAdminEvents::ADMIN_MATCH => ['onAdminMatch', -255]],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::onAdminMatch
     */
    public function testOnAdminMatch(): void
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onAdminMatch($event);

        self::assertSame('pagelayout.html.twig', $event->getPageLayoutTemplate());
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\SetPageLayoutListener::onAdminMatch
     */
    public function testOnAdminMatchWithExistingPageLayout(): void
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MASTER_REQUEST);
        $event->setPageLayoutTemplate('existing.html.twig');

        $this->listener->onAdminMatch($event);

        self::assertSame('existing.html.twig', $event->getPageLayoutTemplate());
    }
}
