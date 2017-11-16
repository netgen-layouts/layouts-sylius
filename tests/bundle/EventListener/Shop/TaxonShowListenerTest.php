<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Context\Context;
use Netgen\BlockManager\Sylius\Tests\Stubs\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TaxonShowListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener
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

    public function setUp()
    {
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->listener = new TaxonShowListener($this->requestStack, $this->context);
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array('sylius.taxon.show' => 'onTaxonShow'),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener::onTaxonShow
     */
    public function testOnTaxonShow()
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Taxon(42);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onTaxonShow($event);

        $this->assertEquals($taxon, $request->attributes->get('ngbm_sylius_taxon'));

        $this->assertTrue($this->context->has('sylius_taxon_id'));
        $this->assertEquals(42, $this->context->get('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\TaxonShowListener::onTaxonShow
     */
    public function testOnTaxonShowWithoutTaxon()
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Product(42);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onTaxonShow($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_taxon'));
        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }
}
