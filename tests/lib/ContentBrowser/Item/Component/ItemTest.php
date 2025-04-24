<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\ContentBrowser\Item\Component;


use Netgen\Layouts\Sylius\API\ComponentInterface;
use Netgen\Layouts\Sylius\ContentBrowser\Item\Component\Item;
use Netgen\Layouts\Sylius\Tests\Stubs\Component;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    private ComponentInterface $component;

    private Item $item;

    protected function setUp(): void
    {
        $this->component = new Component(3, 'My component');

        $this->item = new Item($this->component);
    }

    public function testGetValue(): void
    {
        self::assertSame('component_stub_3', $this->item->getValue());
    }

    public function testGetName(): void
    {
        self::assertSame('My component', $this->item->getName());
    }

    public function testIsVisible(): void
    {
        self::assertTrue($this->item->isVisible());

        $item = new Item(
            new Component(4, 'My disabled component', false),
        );

        self::assertFalse($item->isVisible());
    }

    public function testIsSelectable(): void
    {
        self::assertTrue($this->item->isSelectable());

        $item = new Item(
            new Component(4, 'My disabled component', false),
        );

        self::assertFalse($item->isSelectable());
    }

    public function testGetComponent(): void
    {
        self::assertSame($this->component, $this->item->getComponent());
    }
}
