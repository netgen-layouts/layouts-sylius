<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductShowListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
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
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        $product = $event->getSubject();
        if (!$product instanceof ProductInterface) {
            return;
        }

        $currentRequest->attributes->set('ngbm_sylius_product', $product);
    }
}
