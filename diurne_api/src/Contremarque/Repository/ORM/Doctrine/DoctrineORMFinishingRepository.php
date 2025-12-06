<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Finishing;
use App\Contremarque\Repository\FinishingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMFinishingRepository extends DoctrineORMRepository implements FinishingRepository
{
    protected const ENTITY_CLASS = Finishing::class;
    protected const ALIAS = 'finishing';

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

    public function findRandomFinishing()
    {
        try {
            // Get the total count of units
            $count = $this->manager->createQueryBuilder()
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->select('count(finishing.id)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No units available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random unit
        $query = $this->manager->createQueryBuilder()
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->select(self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
    public function findUnusedFinitions(): array
    {
        $query = $this->manager->createQueryBuilder()
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin('finishing.customerInstruction', 'ci')
            ->where('ci.id IS NULL')
            ->getQuery();

        return $query->getResult();
    }
}
