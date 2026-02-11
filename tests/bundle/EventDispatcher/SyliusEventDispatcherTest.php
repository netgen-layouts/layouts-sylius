<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventDispatcher;

use Netgen\Bundle\LayoutsSyliusBundle\EventDispatcher\SyliusEventDispatcher;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

#[CoversClass(SyliusEventDispatcher::class)]
final class SyliusEventDispatcherTest extends TestCase
{
    private SyliusEventDispatcher $dispatcher;

    private Stub&EventDispatcherInterface $innerEventDispatcherStub;

    private Stub&SymfonyEventDispatcherInterface $eventDispatcherStub;

    protected function setUp(): void
    {
        $this->innerEventDispatcherStub = self::createStub(EventDispatcherInterface::class);
        $this->eventDispatcherStub = self::createStub(SymfonyEventDispatcherInterface::class);

        $this->dispatcher = new SyliusEventDispatcher(
            $this->innerEventDispatcherStub,
            $this->eventDispatcherStub,
        );
    }

    public function testDispatch(): void
    {
        $eventName = 'show';
        $requestConfigurationStub = self::createStub(RequestConfiguration::class);
        $resource = new Product(5);

        $eventStub = self::createStub(ResourceControllerEvent::class);

        $this->innerEventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        $this->eventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        self::assertSame(
            $eventStub,
            $this->dispatcher->dispatch($eventName, $requestConfigurationStub, $resource),
        );
    }

    public function testDispatchMultiple(): void
    {
        $eventName = 'show';
        $requestConfigurationStub = self::createStub(RequestConfiguration::class);
        $resource = new Product(5);

        $eventStub = self::createStub(ResourceControllerEvent::class);

        $this->innerEventDispatcherStub
            ->method('dispatchMultiple')
            ->willReturn($eventStub);

        $this->eventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        self::assertSame(
            $eventStub,
            $this->dispatcher->dispatchMultiple($eventName, $requestConfigurationStub, $resource),
        );
    }

    public function testDispatchPreEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationStub = self::createStub(RequestConfiguration::class);
        $resource = new Product(5);

        $eventStub = self::createStub(ResourceControllerEvent::class);

        $this->innerEventDispatcherStub
            ->method('dispatchPreEvent')
            ->willReturn($eventStub);

        $this->eventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        self::assertSame(
            $eventStub,
            $this->dispatcher->dispatchPreEvent($eventName, $requestConfigurationStub, $resource),
        );
    }

    public function testDispatchPostEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationStub = self::createStub(RequestConfiguration::class);
        $resource = new Product(5);

        $eventStub = self::createStub(ResourceControllerEvent::class);

        $this->innerEventDispatcherStub
            ->method('dispatchPostEvent')
            ->willReturn($eventStub);

        $this->eventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        self::assertSame(
            $eventStub,
            $this->dispatcher->dispatchPostEvent($eventName, $requestConfigurationStub, $resource),
        );
    }

    public function testDispatchInitializeEvent(): void
    {
        $eventName = 'show';
        $requestConfigurationStub = self::createStub(RequestConfiguration::class);
        $resource = new Product(5);

        $eventStub = self::createStub(ResourceControllerEvent::class);

        $this->innerEventDispatcherStub
            ->method('dispatchInitializeEvent')
            ->willReturn($eventStub);

        $this->eventDispatcherStub
            ->method('dispatch')
            ->willReturn($eventStub);

        self::assertSame(
            $eventStub,
            $this->dispatcher->dispatchInitializeEvent($eventName, $requestConfigurationStub, $resource),
        );
    }
}
