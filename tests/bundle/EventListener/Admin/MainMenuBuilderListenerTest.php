<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Admin;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Admin\MainMenuBuilderListener;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use function array_keys;

#[CoversClass(MainMenuBuilderListener::class)]
final class MainMenuBuilderListenerTest extends TestCase
{
    private MainMenuBuilderListener $listener;

    private MockObject&AuthorizationCheckerInterface $authCheckerMock;

    protected function setUp(): void
    {
        $this->authCheckerMock = $this->createMock(AuthorizationCheckerInterface::class);

        $this->listener = new MainMenuBuilderListener($this->authCheckerMock);
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
        $this->authCheckerMock
            ->expects(self::any())
            ->method('isGranted')
            ->with(self::identicalTo('ROLE_NGLAYOUTS_ADMIN'))
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
        $this->authCheckerMock
            ->expects(self::any())
            ->method('isGranted')
            ->with(self::identicalTo('ROLE_NGLAYOUTS_ADMIN'))
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
        $this->authCheckerMock
            ->expects(self::any())
            ->method('isGranted')
            ->with(self::identicalTo('ROLE_NGLAYOUTS_ADMIN'))
            ->willReturn(false);

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        self::assertArrayNotHasKey('nglayouts', $menuItem);
    }
}
