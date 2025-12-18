<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\TargetType;

use Netgen\Layouts\Sylius\Layout\Resolver\TargetType\Page;
use Netgen\Layouts\Sylius\Tests\TestCase\ValidatorTestCaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[CoversClass(Page::class)]
final class PageTest extends TestCase
{
    use ValidatorTestCaseTrait;

    private ValidatorInterface $validator;

    private Page $targetType;

    protected function setUp(): void
    {
        $allowedPages = [
            'sylius_shop_homepage' => 'homepage',
            'sylius_shop_order_show' => 'order_show',
        ];

        $this->validator = $this->createValidator();

        $this->targetType = new Page($allowedPages);
    }

    public function testGetType(): void
    {
        self::assertSame('sylius_page', $this->targetType::getType());
    }

    public function testValidationValid(): void
    {
        $errors = $this->validator->validate('homepage', $this->targetType->getConstraints());
        self::assertCount(0, $errors);
    }

    public function testValidationInvalid(): void
    {
        $errors = $this->validator->validate('search_page', $this->targetType->getConstraints());
        self::assertNotCount(0, $errors);
    }

    public function testProvideValue(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'sylius_shop_order_show');

        self::assertSame('order_show', $this->targetType->provideValue($request));
    }

    public function testProvideValueWithNoRoute(): void
    {
        $request = Request::create('/');

        self::assertNull($this->targetType->provideValue($request));
    }
}
