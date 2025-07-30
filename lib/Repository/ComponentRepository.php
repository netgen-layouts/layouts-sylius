<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Netgen\Layouts\Exception\RuntimeException;
use Netgen\Layouts\Sylius\Component\ComponentId;
use Netgen\Layouts\Sylius\Component\ComponentInterface;
use Pagerfanta\PagerfantaInterface;
use ReflectionClass;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;

use function is_a;
use function sprintf;

/**
 * @extends \Doctrine\ORM\EntityRepository<\Netgen\Layouts\Sylius\Component\ComponentInterface>
 */
final class ComponentRepository extends EntityRepository implements ComponentRepositoryInterface
{
    use ResourceRepositoryTrait;

    /**
     * @var array<string, \Doctrine\ORM\EntityRepository<\Netgen\Layouts\Sylius\Component\ComponentInterface>>
     */
    private array $componentRepositories = [];

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        foreach ($entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
            $class = $metadata->getName();
            $reflectionClass = new ReflectionClass($class);

            if (!is_a($class, ComponentInterface::class, true) || $reflectionClass->isAbstract()) {
                continue;
            }

            $this->componentRepositories[$class::getIdentifier()] = $entityManager->getRepository($class);
        }
    }

    public function load(ComponentId $componentId): ?ComponentInterface
    {
        return $this->resolveRepository($componentId->getComponentType())->find($componentId->getId());
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

    /**
     * @return \Doctrine\ORM\EntityRepository<\Netgen\Layouts\Sylius\Component\ComponentInterface>
     */
    private function resolveRepository(string $identifier): EntityRepository
    {
        return $this->componentRepositories[$identifier] ??
            throw new RuntimeException(
                sprintf('Sylius entity repository for component with identifier "%s" not found!', $identifier),
            );
    }
}
