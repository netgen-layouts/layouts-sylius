<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop;

use Netgen\BlockManager\Context\ContextInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductIndexListener implements EventSubscriberInterface
{
    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var \Sylius\Component\Locale\Context\LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Netgen\BlockManager\Context\ContextInterface
     */
    private $context;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        LocaleContextInterface $localeContext,
        RequestStack $requestStack,
        ContextInterface $context
    ) {
        $this->taxonRepository = $taxonRepository;
        $this->localeContext = $localeContext;
        $this->requestStack = $requestStack;
        $this->context = $context;
    }

    public static function getSubscribedEvents()
    {
        return array('sylius.product.index' => 'onProductIndex');
    }

    /**
     * Sets the currently displayed taxon to the request,
     * to be able to match with layout resolver.
     *
     * @param \Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent $event
     */
    public function onProductIndex(ResourceControllerEvent $event)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return;
        }

        // Only sane way to extract the reference to the taxon
        if (!$currentRequest->attributes->has('slug')) {
            return;
        }

        $taxon = $this->taxonRepository->findOneBySlug(
            $currentRequest->attributes->get('slug'),
            $this->localeContext->getLocaleCode()
        );

        if (!$taxon instanceof TaxonInterface) {
            return;
        }

        $currentRequest->attributes->set('ngbm_sylius_taxon', $taxon);
        // We set context here instead in a ContextProvider, since sylius.taxon.show
        // event happens too late, after onKernelRequest event has already been executed
        $this->context->set('sylius_taxon_id', (int) $taxon->getId());
    }
}
