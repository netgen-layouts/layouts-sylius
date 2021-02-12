<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Shop;

use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductShowListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductShowListenerTest extends TestCase
{
    private ProductShowListener $listener;

    private RequestStack $requestStack;

    private Context $context;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->listener = new ProductShowListener($this->requestStack, $this->context);
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductShowListener::__construct
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductShowListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['sylius.product.show' => 'onProductShow'],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShow(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $product = new Product(42);
        $event = new ResourceControllerEvent($product);
        $this->listener->onProductShow($event);

        self::assertSame($product, $request->attributes->get('nglayouts_sylius_product'));

        self::assertTrue($this->context->has('sylius_product_id'));
        self::assertSame(42, $this->context->get('sylius_product_id'));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShowWithoutProduct(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Taxon(42);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onProductShow($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_product'));
        self::assertFalse($this->context->has('sylius_product_id'));
    }
}
