<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Component;

use Netgen\Layouts\Sylius\Component\AbstractComponentTranslation;
use Netgen\Layouts\Sylius\Tests\Stubs\ComponentTranslation as ComponentTranslationStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractComponentTranslation::class)]
final class AbstractComponentTranslationTest extends TestCase
{
    public function test(): void
    {
        $stub = new ComponentTranslationStub(2, 'Test component');

        self::assertSame(2, $stub->getId());
        self::assertSame('Test component', $stub->getName());

        $stub->setName('New component name');

        self::assertSame(2, $stub->getId());
        self::assertSame('New component name', $stub->getName());
    }
}
