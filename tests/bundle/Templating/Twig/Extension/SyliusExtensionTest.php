<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Extension\SyliusExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

#[CoversClass(SyliusExtension::class)]
final class SyliusExtensionTest extends TestCase
{
    private SyliusExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new SyliusExtension();
    }

    public function testGetFunctions(): void
    {
        self::assertNotEmpty($this->extension->getFunctions());
        self::assertContainsOnlyInstancesOf(TwigFunction::class, $this->extension->getFunctions());
    }
}
