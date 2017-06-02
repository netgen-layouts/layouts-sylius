<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Admin;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilderListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(MainMenuBuilder::EVENT_NAME => 'onMainMenuBuild');
    }

    /**
     * This method adds Netgen Layouts menu items to Sylius admin interface.
     *
     * @param \Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent $event
     */
    public function onMainMenuBuild(MenuBuilderEvent $event)
    {
        if (!$this->authorizationChecker->isGranted('ROLE_NGBM_ADMIN')) {
            return;
        }

        $this->addLayoutsSubMenu($event->getMenu());
    }

    /**
     * Adds the Netgen Layouts submenu to Sylius admin interface.
     *
     * @param \Knp\Menu\ItemInterface $menu
     */
    protected function addLayoutsSubMenu(ItemInterface $menu)
    {
        $menuOrder = $this->getNewMenuOrder($menu);

        $layouts = $menu
            ->addChild('nglayouts')
            ->setLabel('sylius.menu.admin.main.nglayouts.header')
        ;

        $layouts
            ->addChild('layout_resolver', array('route' => 'ngbm_admin_layout_resolver_index'))
            ->setLabel('sylius.menu.admin.main.nglayouts.layout_resolver')
            ->setLabelAttribute('icon', 'random')
        ;

        $layouts
            ->addChild('layouts', array('route' => 'ngbm_admin_layouts_index'))
            ->setLabel('sylius.menu.admin.main.nglayouts.layouts')
            ->setLabelAttribute('icon', 'newspaper')
        ;

        $layouts
            ->addChild('shared_layouts', array('route' => 'ngbm_admin_shared_layouts_index'))
            ->setLabel('sylius.menu.admin.main.nglayouts.shared_layouts')
            ->setLabelAttribute('icon', 'list layout')
        ;

        $menu->reorderChildren($menuOrder);
    }

    /**
     * Returns the new menu order.
     *
     * @param \Knp\Menu\ItemInterface $menu
     *
     * @return array
     */
    protected function getNewMenuOrder(ItemInterface $menu)
    {
        $menuOrder = array_keys($menu->getChildren());
        $configMenuIndex = array_search('configuration', $menuOrder, true);
        if ($configMenuIndex !== false) {
            array_splice($menuOrder, array_search('configuration', $menuOrder, true), 0, array('nglayouts'));

            return $menuOrder;
        }

        $menuOrder[] = 'nglayouts';

        return $menuOrder;
    }
}
