<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Validator\Constraint\Page;
use Netgen\Layouts\Sylius\Validator\PageValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[CoversClass(PageValidator::class)]
final class PageValidatorTest extends ValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Page();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $allowedPages = [
            'sylius_shop_homepage' => 'homepage',
            'sylius_shop_cart_summary' => 'cart_summary',
            'sylius_shop_order_thank_you' => 'order_thank_you',
            'sylius_shop_order_show' => 'order_show',
        ];

        return new PageValidator($allowedPages);
    }

    public function testValidateValid(): void
    {
        $this->assertValid(true, 'homepage');
        $this->assertValid(true, 'cart_summary');
    }

    public function testValidateNull(): void
    {
        $this->assertValid(true, null);
    }

    public function testValidateInvalid(): void
    {
        $this->assertValid(false, 'search_page');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\\Layouts\\Sylius\\Validator\\Constraint\\Page", "Symfony\\Component\\Validator\\Constraints\\NotBlank" given');

        $this->constraint = new NotBlank();
        $this->assertValid(true, 'value');
    }

    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "string", "array" given');

        $this->assertValid(true, []);
    }
}
