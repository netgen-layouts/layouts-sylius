<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;
use Netgen\Layouts\Sylius\Repository\ProductRepositoryInterface;
use Netgen\Layouts\Sylius\Repository\TaxonRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use function mb_trim;

final class LatestProductsHandler implements QueryTypeHandlerInterface
{
    private const int DEFAULT_LIMIT = 12;

    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private TaxonRepositoryInterface $taxonRepository,
        private ChannelContextInterface $channelContext,
        private RequestStack $requestStack,
    ) {}

    public function buildParameters(ParameterBuilderInterface $builder): void
    {
        $builder->add(
            'use_current_taxon',
            ParameterType\Compound\BooleanType::class,
            [
                'reverse' => true,
            ],
        );

        $builder->get('use_current_taxon')->add(
            'parent_taxon_id',
            SyliusParameterType\TaxonType::class,
        );
    }

    public function getValues(Query $query, int $offset = 0, ?int $limit = null): iterable
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return [];
        }

        return $this->productRepository->findLatestByChannelAndTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $currentRequest->getLocale(),
            $offset,
            $limit ?? self::DEFAULT_LIMIT,
        );
    }

    public function getCount(Query $query): int
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return 0;
        }

        return $this->productRepository->countLatestByChannelAndTaxon(
            $this->channelContext->getChannel(),
            $this->getParentTaxon($query),
            $currentRequest->getLocale(),
        );
    }

    public function isContextual(Query $query): bool
    {
        return $query->getParameter('use_current_taxon')->value === true;
    }

    private function getParentTaxon(Query $query): ?TaxonInterface
    {
        if ($query->getParameter('use_current_taxon')->value === true) {
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (!$currentRequest instanceof Request) {
                return null;
            }

            $taxonSlug = mb_trim($currentRequest->attributes->getString('slug'));
            if ($taxonSlug === '') {
                return null;
            }

            return $this->taxonRepository->findOneBySlug($taxonSlug, $currentRequest->getLocale());
        }

        $parentTaxonId = $query->getParameter('parent_taxon_id')->value;
        if (mb_trim($parentTaxonId ?? '') === '') {
            return null;
        }

        return $this->taxonRepository->find($parentTaxonId);
    }
}
