<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop;

use Netgen\BlockManager\Context\ContextInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductShowListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Netgen\BlockManager\Context\ContextInterface
     */
    private $context;

    public function __construct(RequestStack $requestStack, ContextInterface $context)
    {
        $this->requestStack = $requestStack;
        $this->context = $context;
    }

    public static function getSubscribedEvents()
    {
        return array('sylius.product.show' => 'onProductShow');
    }

    /**
     * Sets the currently displayed product to the request,
     * to be able to match with layout resolver.
     *
     * @param \Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent $event
     */
    public function onProductShow(ResourceControllerEvent $event)
    {
        $product = $event->getSubject();
        if (!$product instanceof ProductInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('ngbm_sylius_product', $product);
            // We set context here instead in a ContextProvider, since sylius.product.show
            // event happens too late, after onKernelRequest event has already been executed
            $this->context->set('sylius_product_id', (int) $product->getId());
        }
    }
}
