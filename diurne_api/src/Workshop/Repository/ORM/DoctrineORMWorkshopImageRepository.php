<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\WorkshopImage;
use App\Workshop\Repository\WorkshopImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class DoctrineORMWorkshopImageRepository extends DoctrineORMRepository implements WorkshopImageRepository
{
    protected const ENTITY_CLASS = WorkshopImage::class;
    protected const ALIAS = 'workshopImage';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param WorkshopImage $entity
     * @param bool $flush
     * @return void
     */
    public function save(WorkshopImage $entity, bool $flush = false): void
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


    public function findOneByName(array $criteria): ?WorkshopImage
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