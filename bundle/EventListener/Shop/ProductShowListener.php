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
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        }
    }
}
