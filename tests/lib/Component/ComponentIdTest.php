<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Component;

use InvalidArgumentException;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Tests\Stubs\Component;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ComponentId::class)]
final class ComponentIdTest extends TestCase
{
    public function testInstance(): void
    {
        $componentId = new ComponentId('banner_component', 5);

        self::assertSame(5, $componentId->getId());
        self::assertSame('banner_component-5', (string) $componentId);
        self::assertSame('banner_component', $componentId->getComponentType());
    }

    public function testFromString(): void
    {
        $componentId = ComponentId::fromString('banner_component-5');

        self::assertSame(5, $componentId->getId());
        self::assertSame('banner_component-5', (string) $componentId);
        self::assertSame('banner_component', $componentId->getComponentType());
    }

    public function testFromStringWithInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid component ID "banner_component_5"');

        ComponentId::fromString('banner_component_5');
    }

    public function testFromComponent(): void
    {
        $componentId = ComponentId::fromComponent(
            new Component(4, 'My banner'),
        );

        self::assertSame(4, $componentId->getId());
        self::assertSame('component_stub-4', (string) $componentId);
        self::assertSame('component_stub', $componentId->getComponentType());
    }
}
