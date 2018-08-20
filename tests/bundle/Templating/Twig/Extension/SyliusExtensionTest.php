<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Extension\SyliusExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class SyliusExtensionTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Extension\SyliusExtension
     */
    private $extension;

    public function setUp(): void
    {
        $this->extension = new SyliusExtension();
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Extension\SyliusExtension::getFunctions
     */
    public function testGetFunctions(): void
    {
        self::assertNotEmpty($this->extension->getFunctions());
        self::assertContainsOnlyInstancesOf(TwigFunction::class, $this->extension->getFunctions());
    }
}
