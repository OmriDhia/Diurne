<?php

// src/App/Setting/Repository/ORM/Doctrine/DoctrineORMCarpetCollectionRepository.php

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Repository\CarpetCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCarpetCollectionRepository extends DoctrineORMRepository implements CarpetCollectionRepository
{
    protected const ENTITY_CLASS = CarpetCollection::class;
    protected const ALIAS = 'carpetCollection';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(CarpetCollection $carpetCollection): void
    {
        $this->persist($carpetCollection);
        $this->flush();
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // Implement create() method logic here
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // Implement update() method logic here
    }

    public function findRandomCollection(): ?CarpetCollection
    {
        // Get the total count of CarpetCollections

        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(carpetCollection.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No collections available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random collection
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
