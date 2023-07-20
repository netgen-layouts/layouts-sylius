<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\ResourceType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResourceType::class)]
final class ResourceTypeTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new ResourceType();
        self::assertSame('nglayouts_sylius_resource_type', $constraint->validatedBy());
    }
}
