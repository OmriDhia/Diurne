<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\ValidatedSample;
use App\Contremarque\Repository\ValidatedSampleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMValidatedSampleRepository extends DoctrineORMRepository implements ValidatedSampleRepository
{
    protected const ENTITY_CLASS = ValidatedSample::class;
    protected const ALIAS = 'validatedSample';

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

    public function findRandomValidatedSample()
    {
        try {
            // Get the total count of units
            $count = $this->manager->createQueryBuilder()
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->select('count(validatedSample.id)')
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
    public function findUnusedValidatedSamples(): array
    {
        $query = $this->manager->createQueryBuilder()
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin('validatedSample.customerInstruction', 'ci')
            ->where('ci.id IS NULL')
            ->getQuery();

        return $query->getResult();
    }
}
