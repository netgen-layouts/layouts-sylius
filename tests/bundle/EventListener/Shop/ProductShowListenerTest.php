<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Sylius\Tests\Stubs\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductShowListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener
     */
    private $listener;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    public function setUp()
    {
        $this->requestStack = new RequestStack();

        $this->listener = new ProductShowListener($this->requestStack);
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array('sylius.product.show' => 'onProductShow'),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShow()
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $product = new Product(42);
        $event = new ResourceControllerEvent($product);
        $this->listener->onProductShow($event);

        $this->assertEquals($product, $request->attributes->get('ngbm_sylius_product'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductShowListener::onProductShow
     */
    public function testOnProductShowWithoutProduct()
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Taxon(42);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onProductShow($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_product'));
    }
}
