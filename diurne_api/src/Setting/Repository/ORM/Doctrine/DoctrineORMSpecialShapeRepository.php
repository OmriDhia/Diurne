<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\SpecialShape;
use App\Setting\Repository\SpecialShapeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMSpecialShapeRepository extends DoctrineORMRepository implements SpecialShapeRepository
{
    protected const ENTITY_CLASS = SpecialShape::class;
    protected const ALIAS = 'specialShape';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByLabel(string $label): ?SpecialShape
    {
        return $this->findOneBy(['label' => $label]);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // Implement create() method if needed
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // Implement update() method if needed
    }

    public function save(SpecialShape $specialShape, $flush = false): void
    {
        $this->manager->persist($specialShape);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * Find a random SpecialShape entity.
     */
    public function findRandomSpecialShape(): ?SpecialShape
    {
        // Get the total count of SpecialShape records

        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(specialShape.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No special shapes available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Fetch a random SpecialShape entity based on the random offset
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        try {
            return $query->getOneOrNullResult();
        } catch (NoResultException) {
            // No result found
            return null;
        }
    }
}
