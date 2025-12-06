<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\Conversion;
use App\Setting\Repository\ConversionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMConversionRepository extends DoctrineORMRepository implements ConversionRepository
{
    protected const ENTITY_CLASS = Conversion::class;
    protected const ALIAS = 'conversion';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Conversion $conversion, $flush = false): void
    {
        $this->manager->persist($conversion);

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

    public function getRandomConversion(): ?Conversion
    {
        try {
            $query = $this->manager->createQueryBuilder()
                ->select('COUNT(conversion.id)')
                ->from(self::ENTITY_CLASS, 'conversion')
                ->getQuery();
            $count = $query->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 === $count) {
            return null; // No conversions available
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Now fetch a random result based on that offset
        $query = $this->manager->createQueryBuilder()
            ->select('conversion')
            ->from(self::ENTITY_CLASS, 'conversion')
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
