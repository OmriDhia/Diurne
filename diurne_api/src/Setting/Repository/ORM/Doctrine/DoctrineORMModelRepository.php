<?php

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\CarpetCollection;
use App\Setting\Entity\Model;
use App\Setting\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMModelRepository extends DoctrineORMRepository implements ModelRepository
{
    protected const ENTITY_CLASS = Model::class;
    protected const ALIAS = 'model';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function findOneByCode(string $code): ?Model
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
        // Implement if needed
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // Implement if needed
    }

    public function findRandomModelByCollection(CarpetCollection $carpetCollection): ?Model
    {
        // Get the total count of models for the given CarpetCollection
        try {
            $count = $this->manager->createQueryBuilder()
                ->select('count(model.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->where('model.carpetCollection = :carpetCollection')
                ->setParameter('carpetCollection', $carpetCollection)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No models available for the given CarpetCollection
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random model
        $query = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where('model.carpetCollection = :carpetCollection')
            ->setParameter('carpetCollection', $carpetCollection)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
