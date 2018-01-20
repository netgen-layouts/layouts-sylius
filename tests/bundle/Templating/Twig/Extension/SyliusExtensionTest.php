<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension\SyliusExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class SyliusExtensionTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension\SyliusExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new SyliusExtension();
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\Templating\Twig\Extension\SyliusExtension::getFunctions
     */
    public function testGetFunctions()
    {
        $this->assertNotEmpty($this->extension->getFunctions());

        foreach ($this->extension->getFunctions() as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }
}
