<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Admin;

use Netgen\Bundle\LayoutsAdminBundle\Event\AdminMatchEvent;
use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Admin\SetPageLayoutListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[CoversClass(SetPageLayoutListener::class)]
final class SetPageLayoutListenerTest extends TestCase
{
    private SetPageLayoutListener $listener;

    protected function setUp(): void
    {
        $this->listener = new SetPageLayoutListener('pagelayout.html.twig');
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [AdminMatchEvent::class => ['onAdminMatch', -255]],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnAdminMatch(): void
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MAIN_REQUEST);

        $this->listener->onAdminMatch($event);

        self::assertSame('pagelayout.html.twig', $event->pageLayoutTemplate);
    }

    public function testOnAdminMatchWithExistingPageLayout(): void
    {
        $event = new AdminMatchEvent(Request::create('/'), HttpKernelInterface::MAIN_REQUEST);
        $event->pageLayoutTemplate = 'existing.html.twig';

        $this->listener->onAdminMatch($event);

        self::assertSame('existing.html.twig', $event->pageLayoutTemplate);
    }
}
