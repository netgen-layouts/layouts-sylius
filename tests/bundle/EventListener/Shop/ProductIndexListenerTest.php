<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Shop;

use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ProductIndexListener;
use Netgen\Layouts\Context\Context;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(ProductIndexListener::class)]
final class ProductIndexListenerTest extends TestCase
{
    private ProductIndexListener $listener;

    private MockObject&TaxonRepositoryInterface $taxonRepositoryMock;

    private MockObject&LocaleContextInterface $localeContextMock;

    private RequestStack $requestStack;

    private Context $context;

    protected function setUp(): void
    {
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->localeContextMock
            ->method('getLocaleCode')
            ->willReturn('en');

        $this->listener = new ProductIndexListener(
            $this->taxonRepositoryMock,
            $this->localeContextMock,
            $this->requestStack,
            $this->context,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['sylius.product.index' => 'onProductIndex'],
            $this->listener::getSubscribedEvents(),
        );
    }

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
            ->willReturn($taxon);

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertSame($taxon, $request->attributes->get('nglayouts_sylius_taxon'));

        self::assertTrue($this->context->has('sylius_taxon_id'));
        self::assertSame(42, $this->context->get('sylius_taxon_id'));
    }

    public function testOnProductIndexWithoutRequest(): void
    {
        $this->taxonRepositoryMock
            ->expects(self::never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertFalse($this->context->has('sylius_taxon_id'));
    }

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
        self::assertFalse($request->attributes->has('nglayouts_sylius_resource'));
        self::assertFalse($this->context->has('sylius_taxon_id'));
    }

    public function testOnProductIndexWithNonExistingTaxon(): void
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'unknown');

        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects(self::once())
            ->method('findOneBySlug')
            ->with(self::identicalTo('unknown'), self::identicalTo('en'))
            ->willReturn(null);

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_taxon'));
        self::assertFalse($request->attributes->has('nglayouts_sylius_resource'));
        self::assertFalse($this->context->has('sylius_taxon_id'));
    }
}
