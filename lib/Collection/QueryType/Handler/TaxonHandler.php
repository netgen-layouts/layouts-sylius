<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Collection\QueryType\Handler;

use Netgen\Layouts\API\Values\Collection\Query;
use Netgen\Layouts\Collection\QueryType\QueryTypeHandlerInterface;
use Netgen\Layouts\Parameters\ParameterBuilderInterface;
use Netgen\Layouts\Parameters\ParameterType;
use Netgen\Layouts\Sylius\Parameters\ParameterType as SyliusParameterType;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use function count;
use function trim;

class TaxonHandler implements QueryTypeHandlerInterface
{
    /**
     * @param TaxonRepositoryInterface<TaxonInterface> $taxonRepository
     */
    public function __construct(
        private readonly TaxonRepositoryInterface $taxonRepository,
        private readonly RequestStack $requestStack,
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
            'taxon_id',
            SyliusParameterType\TaxonType::class,
        );
    }

    /**
     * @return iterable<array-key, TaxonInterface>
     */
    public function getValues(Query $query, int $offset = 0, ?int $limit = null): iterable
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return [];
        }

        $taxon = $this->getTaxon($query);

        if (!$taxon instanceof TaxonInterface) {
            return [];
        }

        $taxonCode = $taxon->getCode();
        if ($taxonCode === null) {
            return [];
        }

        /** @var TaxonInterface[] $children */
        $children = $this->taxonRepository->findChildren($taxonCode);

        return $children;
    }

    public function getCount(Query $query): int
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return 0;
        }

        $taxon = $this->getTaxon($query);
        if (!$taxon instanceof TaxonInterface) {
            return 0;
        }

        $taxonCode = $taxon->getCode();
        if ($taxonCode === null) {
            return 0;
        }

        return count($this->taxonRepository->findChildren($taxonCode));
    }

    public function isContextual(Query $query): bool
    {
        return $query->getParameter('use_current_taxon')->getValue() === true;
    }

    private function getTaxon(Query $query): ?TaxonInterface
    {
        if ($this->isContextual($query)) {
            $currentRequest = $this->requestStack->getCurrentRequest();
            if (!$currentRequest instanceof Request) {
                return null;
            }

            $taxonSlug = trim($currentRequest->attributes->get('slug') ?? '');
            if ($taxonSlug === '') {
                return null;
            }

            return $this->taxonRepository->findOneBySlug($taxonSlug, $currentRequest->getLocale());
        }

        $taxonId = $query->getParameter('taxon_id')->getValue();
        if (trim($taxonId ?? '') === '') {
            return null;
        }

        return $this->taxonRepository->find($taxonId);
    }
}
