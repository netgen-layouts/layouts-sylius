<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Admin;

use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilderListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener
     */
    protected $listener;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $authCheckerMock;

    public function setUp()
    {
        $this->authCheckerMock = $this->createMock(AuthorizationCheckerInterface::class);

        $this->listener = new MainMenuBuilderListener($this->authCheckerMock);
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array(MainMenuBuilder::EVENT_NAME => 'onMainMenuBuild'),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::onMainMenuBuild
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::addLayoutsSubMenu
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::getNewMenuOrder
     */
    public function testOnMainMenuBuild()
    {
        $this->authCheckerMock
            ->expects($this->any())
            ->method('isGranted')
            ->with($this->equalTo('ROLE_NGBM_ADMIN'))
            ->will($this->returnValue(true));

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        $this->assertArrayHasKey('nglayouts', $menuItem);

        $this->assertEquals(
            array('layout_resolver', 'layouts', 'shared_layouts'),
            array_keys($menuItem['nglayouts']->getChildren())
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::onMainMenuBuild
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::addLayoutsSubMenu
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::getNewMenuOrder
     */
    public function testOnMainMenuBuildPlacedBeforeConfiguration()
    {
        $this->authCheckerMock
            ->expects($this->any())
            ->method('isGranted')
            ->with($this->equalTo('ROLE_NGBM_ADMIN'))
            ->will($this->returnValue(true));

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $menuItem->addChild('configuration');

        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        $this->assertEquals(
            array('nglayouts', 'configuration'),
            array_keys($menuItem->getChildren())
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin\MainMenuBuilderListener::onMainMenuBuild
     */
    public function testOnMainMenuBuildWithNoAccess()
    {
        $this->authCheckerMock
            ->expects($this->any())
            ->method('isGranted')
            ->with($this->equalTo('ROLE_NGBM_ADMIN'))
            ->will($this->returnValue(false));

        $factory = new MenuFactory();
        $menuItem = new MenuItem('root', $factory);
        $event = new MenuBuilderEvent($factory, $menuItem);
        $this->listener->onMainMenuBuild($event);

        $this->assertArrayNotHasKey('nglayouts', $menuItem);
    }
}