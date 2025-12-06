<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetSpecificTreatment;
use App\Contremarque\Repository\CarpetSpecificTreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMCarpetSpecificTreatmentRepository extends DoctrineORMRepository implements CarpetSpecificTreatmentRepository
{
    protected const ENTITY_CLASS = CarpetSpecificTreatment::class;
    protected const ALIAS = 'carpetSpecificTreatment';

    /**
     * DoctrineORMCarpetSpecificTreatmentRepository constructor.
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

    public function getRandomObject()
    {
        // Get the total count of Quality records
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(carpetSpecificTreatment.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No qualities available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Fetch a random Quality entity based on the random offset
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        try {
            return $query->getOneOrNullResult();
        } catch (NoResultException) {
            return null; // No result found
        }
    }
}
