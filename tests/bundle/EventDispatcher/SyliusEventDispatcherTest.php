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

    private MockObject&EventDispatcherInterface $innerEventDispatcherMock;

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
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $this->innerEventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventName, $requestConfigurationMock, $resource)
            ->willReturn($eventMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventMock, 'nglayouts.sylius.resource.show')
            ->willReturn($eventMock);

        self::assertSame(
            $eventMock,
            $this->dispatcher->dispatch($eventName, $requestConfigurationMock, $resource),
        );
    }

    public function testDispatchMultiple(): void
    {
        $eventName = 'show';
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $this->innerEventDispatcherMock
            ->expects($this->once())
            ->method('dispatchMultiple')
            ->with($eventName, $requestConfigurationMock, $resource)
            ->willReturn($eventMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventMock, 'nglayouts.sylius.resource.show')
            ->willReturn($eventMock);

        self::assertSame(
            $eventMock,
            $this->dispatcher->dispatchMultiple($eventName, $requestConfigurationMock, $resource),
        );
    }

    public function testDispatchPreEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $this->innerEventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPreEvent')
            ->with($eventName, $requestConfigurationMock, $resource)
            ->willReturn($eventMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventMock, 'nglayouts.sylius.resource.pre_show')
            ->willReturn($eventMock);

        self::assertSame(
            $eventMock,
            $this->dispatcher->dispatchPreEvent($eventName, $requestConfigurationMock, $resource),
        );
    }

    public function testDispatchPostEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $this->innerEventDispatcherMock
            ->expects($this->once())
            ->method('dispatchPostEvent')
            ->with($eventName, $requestConfigurationMock, $resource)
            ->willReturn($eventMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventMock, 'nglayouts.sylius.resource.post_show')
            ->willReturn($eventMock);

        self::assertSame(
            $eventMock,
            $this->dispatcher->dispatchPostEvent($eventName, $requestConfigurationMock, $resource),
        );
    }

    public function testDispatchInitializeEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $resource = new Product(5);

        $eventMock = $this->createMock(ResourceControllerEvent::class);

        $this->innerEventDispatcherMock
            ->expects($this->once())
            ->method('dispatchInitializeEvent')
            ->with($eventName, $requestConfigurationMock, $resource)
            ->willReturn($eventMock);

        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($eventMock, 'nglayouts.sylius.resource.initialize_show')
            ->willReturn($eventMock);

        self::assertSame(
            $eventMock,
            $this->dispatcher->dispatchInitializeEvent($eventName, $requestConfigurationMock, $resource),
        );
    }
}
