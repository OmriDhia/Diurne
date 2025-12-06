<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\SpecialTreatment;
use App\Setting\Repository\SpecialTreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMSpecialTreatmentRepository extends DoctrineORMRepository implements SpecialTreatmentRepository
{
    protected const ENTITY_CLASS = SpecialTreatment::class;
    protected const ALIAS = 'specialTreatment';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(SpecialTreatment $specialTreatment, $flush = false): void
    {
        $this->manager->persist($specialTreatment);

        if ($flush) {
            $this->manager->flush();
        }
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
                ->select('count(specialTreatment.id)')
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
