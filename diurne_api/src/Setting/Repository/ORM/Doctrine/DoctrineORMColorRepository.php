<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Color;
use App\Setting\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMColorRepository extends DoctrineORMRepository implements ColorRepository
{
    protected const ENTITY_CLASS = Color::class;
    protected const ALIAS = 'color';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Color $color, $flush = false): void
    {
        $this->manager->persist($color);

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

    public function findRandomColor()
    {
        // Get the total count of materials for the given CarpetCollection
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(color.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No materials available for the given CarpetCollection
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random material
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
