<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\ContentBrowser\Item\Component;

use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\ItemValue;
use Netgen\Layouts\Sylius\Tests\Stubs\Component;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ItemValue::class)]
final class ItemValueTest extends TestCase
{
    public function testFromValue(): void
    {
        $itemValue = ItemValue::fromValue('banner_component_5');

        self::assertSame(5, $itemValue->getId());
        self::assertSame('banner_component_5', $itemValue->getValue());
        self::assertSame('banner_component', $itemValue->getComponentTypeIdentifier());
    }

    public function testFromComponent(): void
    {
        $itemValue = ItemValue::fromComponent(
            new Component(4, 'My banner'),
        );

        self::assertSame(4, $itemValue->getId());
        self::assertSame('component_stub_4', $itemValue->getValue());
        self::assertSame('component_stub', $itemValue->getComponentTypeIdentifier());
    }
}
