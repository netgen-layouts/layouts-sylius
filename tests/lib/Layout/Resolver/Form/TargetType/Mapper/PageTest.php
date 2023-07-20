<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\Page;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(Page::class)]
final class PageTest extends TestCase
{
    private Page $mapper;

    protected function setUp(): void
    {
        $allowedPages = [
            'sylius_shop_homepage' => 'homepage',
            'sylius_shop_cart_summary' => 'cart_summary',
            'sylius_shop_order_thank_you' => 'order_thank_you',
            'sylius_shop_order_show' => 'order_show',
        ];

        $this->mapper = new Page($allowedPages);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        $pagesList = [
            'Homepage' => 'homepage',
            'Cart summary' => 'cart_summary',
            'Order thank you' => 'order_thank_you',
            'Order show' => 'order_show',
        ];

        self::assertSame(
            [
                'choices' => $pagesList,
                'choice_translation_domain' => false,
                'multiple' => false,
                'expanded' => false,
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
