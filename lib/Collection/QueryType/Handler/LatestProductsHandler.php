<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Collection\QueryType\Handler;

use Netgen\BlockManager\API\Values\Collection\Query;
use Netgen\BlockManager\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Doctrine\ORM\ProductRepositoryInterface;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class LatestProductsHandler implements QueryTypeHandlerInterface
{
    private const DEFAULT_LIMIT = 12;

    /**
     * @var \Netgen\Layouts\Sylius\Doctrine\ORM\ProductRepositoryInterface
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

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'use_current_taxon',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
            ]
        );

        $builder->get('use_current_taxon')->add(
            'parent_taxon_id',
            SyliusParameterType\TaxonType::class
        );
    }

    public function getValues(Query $query, $offset = 0, $limit = null)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return [];
        }

        return $this->productRepository->findLatestByTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $currentRequest->getLocale(),
            $offset,
            $limit ?? self::DEFAULT_LIMIT
        );
    }

    public function getCount(Query $query): int
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return 0;
        }

        return $this->productRepository->countLatestByTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $currentRequest->getLocale()
        );
    }

    public function isContextual(Query $query): bool
    {
        return $query->getParameter('use_current_taxon')->getValue() === true;
    }

    private function getParentTaxon(Query $query): ?TaxonInterface
    {
        if ($query->getParameter('use_current_taxon')->getValue() === true) {
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (!$currentRequest instanceof Request) {
                return null;
            }

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
