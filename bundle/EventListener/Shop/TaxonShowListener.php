<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop;

use Netgen\BlockManager\Context\ContextInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TaxonShowListener implements EventSubscriberInterface
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
        return array('sylius.taxon.show' => 'onTaxonShow');
    }

    /**
     * Sets the currently displayed taxon to the request,
     * to be able to match with layout resolver.
     *
     * @param \Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent $event
     */
    public function onTaxonShow(ResourceControllerEvent $event)
    {
        $taxon = $event->getSubject();
        if (!$taxon instanceof TaxonInterface) {
            return;
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest instanceof Request) {
            $currentRequest->attributes->set('ngbm_sylius_taxon', $taxon);
            // We set context here instead in a ContextProvider, since sylius.taxon.show
            // event happens too late, after onKernelRequest event has already been executed
            $this->context->set('sylius_taxon_id', (int) $taxon->getId());
        }
    }
}
