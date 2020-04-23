<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Admin;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function array_keys;
use function array_search;
use function array_splice;

final class MainMenuBuilderListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents(): array
    {
        return [MainMenuBuilder::EVENT_NAME => 'onMainMenuBuild'];
    }

    /**
     * This method adds Netgen Layouts menu items to Sylius admin interface.
     */
    public function onMainMenuBuild(MenuBuilderEvent $event): void
    {
        if (!$this->authorizationChecker->isGranted('ROLE_NGLAYOUTS_ADMIN')) {
            return;
        }

        $this->addLayoutsSubMenu($event->getMenu());
    }

    /**
     * Adds the Netgen Layouts submenu to Sylius admin interface.
     */
    private function addLayoutsSubMenu(ItemInterface $menu): void
    {
        $menuOrder = $this->getNewMenuOrder($menu);

        $layouts = $menu
            ->addChild('nglayouts')
            ->setLabel('sylius.menu.admin.main.nglayouts.header');

        $layouts
            ->addChild('layout_resolver', ['route' => 'nglayouts_admin_layout_resolver_index'])
            ->setLabel('sylius.menu.admin.main.nglayouts.layout_resolver')
            ->setLabelAttribute('icon', 'random');

        $layouts
            ->addChild('layouts', ['route' => 'nglayouts_admin_layouts_index'])
            ->setLabel('sylius.menu.admin.main.nglayouts.layouts')
            ->setLabelAttribute('icon', 'newspaper');

        $layouts
            ->addChild('shared_layouts', ['route' => 'nglayouts_admin_shared_layouts_index'])
            ->setLabel('sylius.menu.admin.main.nglayouts.shared_layouts')
            ->setLabelAttribute('icon', 'list layout');

        $menu->reorderChildren($menuOrder);
    }

    /**
     * Returns the new menu order.
     *
     * @return string[]
     */
    private function getNewMenuOrder(ItemInterface $menu): array
    {
        $menuOrder = array_keys($menu->getChildren());
        $configMenuIndex = array_search('configuration', $menuOrder, true);
        if ($configMenuIndex !== false) {
            array_splice($menuOrder, $configMenuIndex, 0, ['nglayouts']);

            return $menuOrder;
        }

        $menuOrder[] = 'nglayouts';

        return $menuOrder;
    }
}
