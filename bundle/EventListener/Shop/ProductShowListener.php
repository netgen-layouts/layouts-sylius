<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\EventListener\Shop;

use Netgen\Layouts\Context\Context;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductShowListener implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack, private Context $context)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['sylius.product.show' => 'onProductShow'];
    }

    /**
     * Sets the currently displayed product to the request,
     * to be able to match with layout resolver.
     */
    public function onProductShow(ResourceControllerEvent $event): void
    {
        $product = $event->getSubject();
        if (!$product instanceof ProductInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('nglayouts_sylius_product', $product);
            // We set context here instead in a ContextProvider, since sylius.product.show
            // event happens too late, after onKernelRequest event has already been executed
            $this->context->set('sylius_product_id', (int) $product->getId());
        }
    }
}
