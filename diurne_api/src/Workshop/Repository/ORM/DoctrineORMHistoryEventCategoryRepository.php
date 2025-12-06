<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Repository\HistoryEventCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;


class DoctrineORMHistoryEventCategoryRepository extends DoctrineORMRepository implements HistoryEventCategoryRepository
{
    protected const ENTITY_CLASS = HistoryEventCategory::class;
    protected const ALIAS = 'historyEventCategory';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param HistoryEventCategory $entity
     * @param bool $flush
     * @return void
     */
    public function save(HistoryEventCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $entity
     * @param array $data
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param int $id
     * @return HistoryEventCategory|null
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */

    public function findOneByName(array $criteria): ?HistoryEventCategory
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS);

        foreach ($criteria as $field => $value) {
            $parameter = str_replace('.', '_', $field);
            $qb->andWhere(self::ALIAS . '.' . $field . ' = :' . $parameter)
                ->setParameter($parameter, $value);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}