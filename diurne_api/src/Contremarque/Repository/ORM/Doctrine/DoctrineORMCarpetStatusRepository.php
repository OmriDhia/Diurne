<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetStatus;
use App\Contremarque\Repository\CarpetStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMCarpetStatusRepository extends DoctrineORMRepository implements CarpetStatusRepository
{
    protected const ENTITY_CLASS = CarpetStatus::class;
    protected const ALIAS = 'carpetStatus';

    /**
     * DoctrineORMCarpetStatusRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    public function findRandomStatus(): ?CarpetStatus
    {
        try {
            // Get the total count of statuses
            $count = $this->manager->createQueryBuilder()
                ->select('count(carpetStatus.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No statuses available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random status
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getStatusByName($name)
    {
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where(self::ALIAS . '.name = :name')
            ->setMaxResults(1)
            ->getQuery();

        $query->setParameter('name', $name);  // No need for additional quotes

        return $query->getOneOrNullResult();
    }
}
