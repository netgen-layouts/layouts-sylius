<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator\Constraint;

use Netgen\Layouts\Sylius\Validator\Constraint\Locale;
use PHPUnit\Framework\TestCase;

final class LocaleTest extends TestCase
{
    /**
     * @covers \Netgen\Layouts\Sylius\Validator\Constraint\Locale::validatedBy
     */
    public function testValidatedBy(): void
    {
        $constraint = new Locale();
        self::assertSame('nglayouts_sylius_locale', $constraint->validatedBy());
    }
}
