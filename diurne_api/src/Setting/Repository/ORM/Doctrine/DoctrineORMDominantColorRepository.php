<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\DominantColor;
use App\Setting\Repository\DominantColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMDominantColorRepository extends DoctrineORMRepository implements DominantColorRepository
{
    protected const ENTITY_CLASS = DominantColor::class;
    protected const ALIAS = 'dominantColor';

    /**
     * DoctrineORMDominantColorRepository constructor.
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

    public function save(DominantColor $dominantColor, $flush = false): void
    {
        $this->manager->persist($dominantColor);

        if ($flush) {
            $this->manager->flush();
        }
    }

    public function findRandomColor()
    {
        // Get the total count of materials for the given CarpetCollection

        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(dominantColor.id)')
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
