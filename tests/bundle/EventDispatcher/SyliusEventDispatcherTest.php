<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventDispatcher;

use Netgen\Bundle\LayoutsSyliusBundle\EventDispatcher\SyliusEventDispatcher;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

#[CoversClass(SyliusEventDispatcher::class)]
final class SyliusEventDispatcherTest extends TestCase
{
    private SyliusEventDispatcher $dispatcher;

    private EventDispatcherInterface&MockObject $innerEventDispatcherMock;

    private MockObject&SymfonyEventDispatcherInterface $eventDispatcherMock;

    protected function setUp(): void
    {
        $this->innerEventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $this->eventDispatcherMock = $this->createMock(SymfonyEventDispatcherInterface::class);

        $this->dispatcher = new SyliusEventDispatcher(
            $this->innerEventDispatcherMock,
            $this->eventDispatcherMock,
        );
    }

    public function testDispatch(): void
    {
        $eventName = 'show';
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $event = new ResourceControllerEvent();

        $this->innerEventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($eventName, $requestConfiguration, $resource)
            ->willReturn($event);

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($event, 'sylius.resource.show')
            ->willReturn($event);

        self::assertSame(
            $event,
            $this->dispatcher->dispatch($eventName, $requestConfiguration, $resource),
        );
    }

    public function testDispatchMultiple(): void
    {
        $eventName = 'show';
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $event = new ResourceControllerEvent();

        $this->innerEventDispatcherMock
            ->expects(self::once())
            ->method('dispatchMultiple')
            ->with($eventName, $requestConfiguration, $resource)
            ->willReturn($event);

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($event, 'sylius.resource.show')
            ->willReturn($event);

        self::assertSame(
            $event,
            $this->dispatcher->dispatchMultiple($eventName, $requestConfiguration, $resource),
        );
    }

    public function testDispatchPreEvent(): void
    {
        $eventName = 'show';
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $event = new ResourceControllerEvent();

        $this->innerEventDispatcherMock
            ->expects(self::once())
            ->method('dispatchPreEvent')
            ->with($eventName, $requestConfiguration, $resource)
            ->willReturn($event);

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($event, 'sylius.resource.pre_show')
            ->willReturn($event);

        self::assertSame(
            $event,
            $this->dispatcher->dispatchPreEvent($eventName, $requestConfiguration, $resource),
        );
    }

    public function testDispatchPostEvent(): void
    {
        $eventName = 'show';
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $event = new ResourceControllerEvent();

        $this->innerEventDispatcherMock
            ->expects(self::once())
            ->method('dispatchPostEvent')
            ->with($eventName, $requestConfiguration, $resource)
            ->willReturn($event);

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($event, 'sylius.resource.post_show')
            ->willReturn($event);

        self::assertSame(
            $event,
            $this->dispatcher->dispatchPostEvent($eventName, $requestConfiguration, $resource),
        );
    }

    public function testDispatchInitializeEvent(): void
    {
        $eventName = 'show';
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $event = new ResourceControllerEvent();

        $this->innerEventDispatcherMock
            ->expects(self::once())
            ->method('dispatchInitializeEvent')
            ->with($eventName, $requestConfiguration, $resource)
            ->willReturn($event);

        $this->eventDispatcherMock
            ->expects(self::once())
            ->method('dispatch')
            ->with($event, 'sylius.resource.initialize_show')
            ->willReturn($event);

        self::assertSame(
            $event,
            $this->dispatcher->dispatchInitializeEvent($eventName, $requestConfiguration, $resource),
        );
    }
}
