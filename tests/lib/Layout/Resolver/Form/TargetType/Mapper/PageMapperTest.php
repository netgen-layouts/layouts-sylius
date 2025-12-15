<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\TargetType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\TargetType\Mapper\PageMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[CoversClass(PageMapper::class)]
final class PageMapperTest extends TestCase
{
    private PageMapper $mapper;

    protected function setUp(): void
    {
        $allowedPages = [
            'sylius_shop_homepage' => 'homepage',
            'sylius_shop_cart_summary' => 'cart_summary',
            'sylius_shop_order_thank_you' => 'order_thank_you',
            'sylius_shop_order_show' => 'order_show',
        ];

        $this->mapper = new PageMapper($allowedPages);
    }

    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    public function testGetFormOptions(): void
    {
        self::assertSame(
            [
                'homepage',
                'cart_summary',
                'order_thank_you',
                'order_show',
            ],
            $this->mapper->getFormOptions()['choices'],
        );
    }
}
