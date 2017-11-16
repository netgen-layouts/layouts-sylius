<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Collection\QueryType\Handler;

use Netgen\BlockManager\API\Values\Collection\Query;
use Netgen\BlockManager\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType;
use Netgen\BlockManager\Sylius\Doctrine\ORM\ProductRepositoryInterface;
use Netgen\BlockManager\Sylius\Parameters\ParameterType as SyliusParameterType;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LatestProductsHandler implements QueryTypeHandlerInterface
{
    /**
     * @var \Netgen\BlockManager\Sylius\Doctrine\ORM\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var \Sylius\Component\Channel\Context\ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TaxonRepositoryInterface $taxonRepository,
        ChannelContextInterface $channelContext,
        RequestStack $requestStack
    ) {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
        $this->channelContext = $channelContext;
        $this->requestStack = $requestStack;
    }

    public function buildParameters(ParameterBuilderInterface $builder)
    {
        $builder->add(
            'use_current_taxon',
            ParameterType\Compound\BooleanType::class,
            array(
                'reverse' => true,
            )
        );

        $builder->get('use_current_taxon')->add(
            'parent_taxon_id',
            SyliusParameterType\TaxonType::class
        );
    }

    public function getValues(Query $query, $offset = 0, $limit = null)
    {
        return $this->productRepository->findLatestByTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $this->requestStack->getMasterRequest()->getLocale(),
            $offset,
            $limit
        );
    }

    public function getCount(Query $query)
    {
        return $this->productRepository->countLatestByTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $this->requestStack->getMasterRequest()->getLocale()
        );
    }

    public function isContextual(Query $query)
    {
        return $query->getParameter('use_current_taxon')->getValue() === true;
    }

    private function getParentTaxon(Query $query): ?TaxonInterface
    {
        if ($query->getParameter('use_current_taxon')->getValue()) {
            $currentRequest = $this->requestStack->getCurrentRequest();

            $taxonSlug = $currentRequest->attributes->get('slug');
            if (empty($taxonSlug)) {
                return null;
            }

            return $this->taxonRepository->findOneBySlug($taxonSlug, $currentRequest->getLocale());
        }

        $parentTaxonId = $query->getParameter('parent_taxon_id')->getValue();
        if (empty($parentTaxonId)) {
            return null;
        }

        return $this->taxonRepository->find($parentTaxonId);
    }
}
