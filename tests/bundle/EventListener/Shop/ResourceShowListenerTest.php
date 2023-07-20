<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Tests\EventListener\Shop;

use Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop\ResourceShowListener;
use Netgen\Layouts\Sylius\Tests\Stubs\Product;
use Netgen\Layouts\Sylius\Tests\Stubs\Taxon;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(ResourceShowListener::class)]
final class ResourceShowListenerTest extends TestCase
{
    private ResourceShowListener $listener;

    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();

        $this->listener = new ResourceShowListener($this->requestStack);
    }

    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            ['sylius.resource.show' => 'onResourceShow'],
            $this->listener::getSubscribedEvents(),
        );
    }

    public function testOnResourceShowProduct(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $product = new Product(42);
        $event = new ResourceControllerEvent($product);
        $this->listener->onResourceShow($event);

        self::assertSame($product, $request->attributes->get('nglayouts_sylius_resource'));
    }

    public function testOnResourceShowTaxon(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $taxon = new Taxon(2);
        $event = new ResourceControllerEvent($taxon);
        $this->listener->onResourceShow($event);

        self::assertSame($taxon, $request->attributes->get('nglayouts_sylius_resource'));
    }

    public function testOnResourceShowInvalid(): void
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $event = new ResourceControllerEvent([]);
        $this->listener->onResourceShow($event);

        self::assertFalse($request->attributes->has('nglayouts_sylius_resource'));
    }
}
