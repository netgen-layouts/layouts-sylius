<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class Controller extends AbstractController
{
    /**
     * Performs access checks on the controller.
     */
    abstract public function checkPermissions(): void;
}
