<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\Location;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMLocationRepository extends DoctrineORMRepository implements LocationRepository
{
    protected const ENTITY_CLASS = Location::class;
    protected const ALIAS = 'location';

    /**
     * DoctrineORMLocationRepository constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository
    ) {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
    }

    /**
     * @param object $location
     *
     * @return void
     */
    public function update($location, array $data)
    {
    }

    public function findRandomLocationByContremarque(Contremarque $contremarque)
    {
        try {
            // Get the total count of locations for the given Contremarque
            $count = $this->manager->createQueryBuilder()
                ->select('count(location.id)')
                ->from(self::ENTITY_CLASS, self::ALIAS)
                ->where('location.contremarque = :contremarque')
                ->setParameter('contremarque', $contremarque)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }
        if (0 == $count) {
            return null; // No locations available for the given Contremarque
        }

        // Generate a random offset
        $randomOffset = random_int(0, $count - 1);

        // Retrieve the random location
        $query = $this->manager->createQueryBuilder()
            ->select('location')
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where('location.contremarque = :contremarque')
            ->setParameter('contremarque', $contremarque)
            ->setFirstResult($randomOffset)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getLastModifiedOrder($location)
    {
        $sql = "SELECT i.carpet_design_order_id FROM image i
                LEFT JOIN image_type it ON(it.id=i.image_type_id)
                INNER JOIN carpet_design_order cdo ON(cdo.id=i.carpet_design_order_id)
                AND it.name='Vignette'
                AND cdo.location_id=".(int) $location->getId().' 
                ORDER BY i.id DESC
                ';

        // Prepare and execute the statement with a bound parameter
        $stmt = $this->manager->getConnection()->prepare($sql);
        // Fetch the results
        $result = $stmt->execute()->fetchOne();

        // Check if any IDs are found
        if (empty($result)) {
            return null;
        }

        $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int) $result);

        // Find the entities using the extracted IDs
        return $carpetDesignOrder;
    }
}
