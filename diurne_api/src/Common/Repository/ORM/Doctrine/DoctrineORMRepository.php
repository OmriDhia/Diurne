<?php

declare(strict_types=1);

namespace App\Common\Repository\ORM\Doctrine;

use Iterator;
use App\Common\Repository\BaseRepository;
use App\Common\Repository\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DoctrineORMRepository.
 *
 * This is a custom abstract Doctrine ORM repository. It is meant to be extended by
 * every Doctrine ORM repository existing in your project.
 *
 * The main features and differences with the EntityRepository provided by Doctrine is
 * that this one implements our repository contract in an immutable way.
 */
abstract class DoctrineORMRepository implements BaseRepository
{
    /**
     * This is Doctrine's Entity Manager. It's fine to expose it to the child class.
     *
     * @var EntityManagerInterface
     */
    protected $manager;
    /**
     * We don't want to expose the query builder to child classes.
     * This is so we are sure the original reference is not modified.
     *
     * We control the query builder state by providing clones with the `query`
     * method and by cloning it with the `filter` method.
     *
     * @var QueryBuilder
     */
    private $queryBuilder;
    private string $entityClass;
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    /**
     * DoctrineORMRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager, string $entityClass, string $alias)
    {
        $this->manager = $manager;
        $this->queryBuilder = $this->manager->createQueryBuilder()
            ->select($alias)
            ->from($entityClass, $alias);
        $this->entityClass = $entityClass;
    }

    /**
     * @return null|object
     */
    public function find(int $id)
    {
        return $this->manager->getRepository($this->entityClass)->find($id);
    }

    /**
     * @return object[]
     *
     * @psalm-return list<object>
     */
    public function findAll()
    {
        return $this->manager->getRepository($this->entityClass)->findAll();
    }

    /**
     * @return object[]
     *
     * @psalm-return list<object>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
    {
        return $this->manager->getRepository($this->entityClass)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return null|object
     */
    public function findOneBy(array $criteria)
    {
        return $this->manager->getRepository($this->entityClass)->findOneBy($criteria);
    }

    // Similar implementations for findBy, findOneBy, etc. (delegate to Doctrine repository)

    abstract public function create(array $data);

    abstract public function update($entity, array $data);

    /**
     * @return void
     */
    public function remove(object $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function persist(object $entity): void
    {
        $this->manager->persist($entity);
    }

    public function flush(): void
    {
        $this->manager->flush();
    }

    public function paginator($query = null): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $firstResult = ($this->page - 1) * $this->itemsPerPage;
        $maxResults = $this->itemsPerPage;

        if (empty($query)) {
            $query = $repository->queryBuilder->getQuery();
            $this->filter(static function (QueryBuilder $qb) use ($firstResult, $maxResults) {
                $qb->setFirstResult($firstResult)->setMaxResults($maxResults);
            });
        } else {
            $query->setFirstResult($firstResult)->setMaxResults($maxResults);
        }

        /** @var Paginator<T> $paginator */
        $paginator = new Paginator($query);

        return new DoctrineORMPaginator($paginator);
    }

    public function withPagination(int $page, int $itemsPerPage): static
    {
        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    public function getIterator(): Iterator
    {
        yield from new Paginator($this->queryBuilder->getQuery());
    }

    public function count(): int
    {
        $paginator = new Paginator($this->queryBuilder->getQuery());

        return $paginator->count();
    }

    /**
     * Filters the repository using the query builder.
     *
     * It clones it and returns a new instance with the modified
     * query builder, so the original reference is preserved.
     *
     * @return $this
     */
    public function filter(callable $filter): self
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    /**
     * Returns a cloned instance of the query builder.
     *
     * Use this to perform single result queries.
     */
    public function query(): QueryBuilder
    {
        return clone $this->queryBuilder;
    }

    /**
     * We allow cloning only from this scope.
     * Also, we clone the query builder always.
     */
    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->manager;
    }
}
