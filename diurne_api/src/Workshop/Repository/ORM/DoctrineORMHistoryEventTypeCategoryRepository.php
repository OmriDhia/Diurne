<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\HistoryEventTypeCategory;
use App\Workshop\Repository\HistoryEventTypeCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;


class DoctrineORMHistoryEventTypeCategoryRepository extends DoctrineORMRepository implements HistoryEventTypeCategoryRepository
{
    protected const ENTITY_CLASS = HistoryEventTypeCategory::class;
    protected const ALIAS = 'historyEventTypeCategory';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param HistoryEventTypeCategory $entity
     * @param bool $flush
     * @return void
     */
    public function save(HistoryEventTypeCategory $entity, bool $flush = false): void
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
}