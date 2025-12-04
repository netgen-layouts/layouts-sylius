<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Admin;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Admin\MainMenuBuilderListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use function array_keys;

#[CoversClass(MainMenuBuilderListener::class)]
final class MainMenuBuilderListenerTest extends TestCase
{
    private MainMenuBuilderListener $listener;

    private Stub&AuthorizationCheckerInterface $authCheckerStub;

    protected function setUp(): void
    {
        $this->authCheckerStub = self::createStub(AuthorizationCheckerInterface::class);

        $this->listener = new MainMenuBuilderListener($this->authCheckerStub);
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [MainMenuBuilder::EVENT_NAME => 'onMainMenuBuild'],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnMainMenuBuild(): void
    {
        $this->authCheckerStub
            ->method('isGranted')
            ->with(self::identicalTo('nglayouts:ui:access'))
            ->willReturn(true);

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        self::assertInstanceOf(ItemInterface::class, $menuItem->getChild('nglayouts'));

        self::assertSame(
            ['layout_resolver', 'layouts', 'shared_layouts', 'transfer'],
            array_keys($menuItem->getChild('nglayouts')->getChildren()),
        );
    }

    public function testOnMainMenuBuildPlacedBeforeConfiguration(): void
    {
        $this->authCheckerStub
            ->method('isGranted')
            ->with(self::identicalTo('nglayouts:ui:access'))
            ->willReturn(true);

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $menuItem->addChild('configuration');

        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        self::assertSame(
            ['nglayouts', 'configuration'],
            array_keys($menuItem->getChildren()),
        );
    }

    public function testOnMainMenuBuildWithNoAccess(): void
    {
        $this->authCheckerStub
            ->method('isGranted')
            ->with(self::identicalTo('nglayouts:ui:access'))
            ->willReturn(false);

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        self::assertArrayNotHasKey('nglayouts', $menuItem);
    }
}
