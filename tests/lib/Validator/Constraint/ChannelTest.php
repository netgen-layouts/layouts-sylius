<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Channel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Channel::class)]
final class ChannelTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new Channel();
        self::assertSame('nglayouts_sylius_channel', $constraint->validatedBy());
    }
}
