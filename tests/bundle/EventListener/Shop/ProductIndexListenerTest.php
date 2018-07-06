<?php

declare(strict_types=1);

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Context\Context;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductIndexListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener
     */
    private $listener;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $taxonRepositoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $localeContextMock;

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
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->localeContextMock
            ->expects($this->any())
            ->method('getLocaleCode')
            ->will($this->returnValue('en'));

        $this->listener = new ProductIndexListener(
            $this->taxonRepositoryMock,
            $this->localeContextMock,
            $this->requestStack,
            $this->context
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        $this->assertSame(
            ['sylius.product.index' => 'onProductIndex'],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndex(): void
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'mugs');

        $this->requestStack->push($request);

        $taxon = new Taxon(42);

        $this->taxonRepositoryMock
            ->expects($this->once())
            ->method('findOneBySlug')
            ->with($this->identicalTo('mugs'), $this->identicalTo('en'))
            ->will($this->returnValue($taxon));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertSame($taxon, $request->attributes->get('ngbm_sylius_taxon'));

        $this->assertTrue($this->context->has('sylius_taxon_id'));
        $this->assertSame(42, $this->context->get('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutRequest(): void
    {
        $this->taxonRepositoryMock
            ->expects($this->never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutSlug(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects($this->never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_taxon'));
        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithNonExistingTaxon(): void
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'unknown');

        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects($this->once())
            ->method('findOneBySlug')
            ->with($this->identicalTo('unknown'), $this->identicalTo('en'))
            ->will($this->returnValue(null));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_taxon'));
        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }
}
