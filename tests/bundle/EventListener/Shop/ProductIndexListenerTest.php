<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Context\Context;
use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductIndexListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener
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
            ->expects(self::any())
            ->method('getLocaleCode')
            ->will(self::returnValue('en'));

        $this->listener = new ProductIndexListener(
            $this->taxonRepositoryMock,
            $this->localeContextMock,
            $this->requestStack,
            $this->context
        );
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::__construct
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['sylius.product.index' => 'onProductIndex'],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndex(): void
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'mugs');

        $this->requestStack->push($request);

        $taxon = new Taxon(42);

        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('findOneBySlug')
            ->with(self::identicalTo('mugs'), self::identicalTo('en'))
            ->will(self::returnValue($taxon));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertSame($taxon, $request->attributes->get('nglayouts_sylius_taxon'));

        self::assertTrue($this->context->has('sylius_taxon_id'));
        self::assertSame(42, $this->context->get('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutRequest(): void
    {
        $this->taxonRepositoryMock
            ->expects(self::never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutSlug(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects(self::never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_taxon'));
        self::assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithNonExistingTaxon(): void
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'unknown');

        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('findOneBySlug')
            ->with(self::identicalTo('unknown'), self::identicalTo('en'))
            ->will(self::returnValue(null));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_taxon'));
        self::assertFalse($this->context->has('sylius_taxon_id'));
    }
}
