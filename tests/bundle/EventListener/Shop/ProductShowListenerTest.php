<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Context\Context;
use Netgen\BlockManager\Sylius\Tests\Stubs\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductShowListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener
     */
    private $listener;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Netgen\BlockManager\Context\Context
     */
    private $context;

    public function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->listener = new ProductShowListener($this->requestStack, $this->context);
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['sylius.product.show' => 'onProductShow'],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShow(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $product = new Product(42);
        $event = new ResourceControllerEvent($product);
        $this->listener->onProductShow($event);

        self::assertSame($product, $request->attributes->get('ngbm_sylius_product'));

        self::assertTrue($this->context->has('sylius_product_id'));
        self::assertSame(42, $this->context->get('sylius_product_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShowWithoutProduct(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Taxon(42);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onProductShow($event);

        self::assertFalse($request->attributes->has('ngbm_sylius_product'));
        self::assertFalse($this->context->has('sylius_product_id'));
    }
}
