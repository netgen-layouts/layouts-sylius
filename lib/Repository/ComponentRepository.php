<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Http\Discovery\Exception\NotFoundException;
use Netgen\Layouts\Sylius\API\ComponentInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;
use ReflectionClass;

use function sprintf;

final class ComponentRepository extends EntityRepository implements ComponentRepositoryInterface
{
    /**
     * @var array<string, \Sylius\Resource\Doctrine\Persistence\RepositoryInterface>
     */
    private array $componentRepositories = [];

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        foreach ($entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
            $class = $metadata->getName();
            $reflClass = new ReflectionClass($class);
            if ($reflClass->implementsInterface(ComponentInterface::class) && !$reflClass->isAbstract()) {
                $componentTypeIdentifier = $class::getIdentifier();

                $this->componentRepositories[$componentTypeIdentifier] = $entityManager->getRepository($class);
            }
        }
    }

    public function load(string $identifier, int $id): ?ComponentInterface
    {
        return $this->resolveRepository($identifier)->find($id);
    }

    public function createListPaginator(string $identifier, string $localeCode): PagerfantaInterface
    {
        return $this->getPaginator($this->createListQueryBuilder($identifier, $localeCode));
    }

    public function createSearchPaginator(string $identifier, string $searchText, string $localeCode): PagerfantaInterface
    {
        return $this->getPaginator($this->createSearchQueryBuilder($identifier, $searchText, $localeCode));
    }

    private function createListQueryBuilder(string $identifier, string $localeCode): QueryBuilder
    {
        return $this->resolveRepository($identifier)->createQueryBuilder('c')
            ->addSelect('translation')
            ->leftJoin('c.translations', 'translation', 'WITH', 'translation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode);
    }

    private function createSearchQueryBuilder(string $identifier, string $searchText, string $localeCode): QueryBuilder
    {
        $queryBuilder = $this->createListQueryBuilder($identifier, $localeCode);
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'translation.name LIKE :search',
                ),
            )
            ->setParameter('search', '%' . $searchText . '%');

        return $queryBuilder;
    }

    private function resolveRepository(string $identifier): EntityRepository
    {
        $repository = $this->componentRepositories[$identifier];

        if (!$repository instanceof EntityRepository) {
            throw new NotFoundException(
                sprintf('Sylius entity repository for component with identifier %s not found!', $identifier),
            );
        }

        return $repository;
    }
}
