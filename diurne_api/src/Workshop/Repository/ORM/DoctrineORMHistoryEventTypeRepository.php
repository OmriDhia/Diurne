<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Entity\HistoryEventCategory;
use App\Workshop\Repository\HistoryEventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;


class DoctrineORMHistoryEventTypeRepository extends DoctrineORMRepository implements HistoryEventTypeRepository
{
    protected const ENTITY_CLASS = HistoryEventType::class;
    protected const ALIAS = 'historyEventType';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param HistoryEventType $entity
     * @param bool $flush
     * @return void
     */
    public function save(HistoryEventType $entity, bool $flush = false): void
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


    public function findOneByName(array $criteria): ?HistoryEventType
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

    public function findByHistoryEventCategory(HistoryEventCategory $category): array
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->select('t')
            ->from(self::ENTITY_CLASS, 't')
            ->join('t.historyEventTypeCategories', 'tc')
            ->where('tc.eventCategoryId = :category')
            ->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }
}
