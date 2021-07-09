<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Channel;
use PHPUnit\Framework\TestCase;

final class ChannelTest extends TestCase
{
    /**
     * @covers \Netgen\Layouts\Sylius\Validator\Constraint\Channel::validatedBy
     */
    public function testValidatedBy(): void
    {
        $constraint = new Channel();
        self::assertSame('nglayouts_sylius_channel', $constraint->validatedBy());
    }
}
