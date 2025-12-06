<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\Image;
use App\Contremarque\Entity\Location;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class DoctrineORMCarpetDesignOrderRepository extends DoctrineORMRepository implements CarpetDesignOrderRepository
{
    protected const ENTITY_CLASS = CarpetDesignOrder::class;
    protected const ALIAS = 'carpetDesignOrder';

    /**
     * DoctrineORMCarpetDesignOrderRepository constructor.
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

    public function findByContremarqueAndProjectDi(int $contremarqueId, int $projectDiId): array
    {
        return $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)  // Add this line to set the root entity and alias
            ->innerJoin(self::ALIAS . '.projectDi', 'p')
            ->innerJoin('p.contremarque', 'cm')
            ->andWhere('cm.id = :contremarqueId')
            ->andWhere('p.id = :projectDiId')
            ->andWhere(self::ALIAS . '.deletedAt IS NULL')
            ->setParameter('contremarqueId', $contremarqueId)
            ->setParameter('projectDiId', $projectDiId)
            ->getQuery()
            ->getResult();
    }

    public function hasVignetteImage($carpetDesignOrderId): bool
    {
        $qb = $this->manager->createQueryBuilder()
            ->select('COUNT(i.id)')
            ->from(self::ENTITY_CLASS, 'carpetDesignOrder')
            ->join('carpetDesignOrder.images', 'i')
            ->join('i.imageType', 'it')
            ->where('it.name = :vignette')
            ->andWhere('carpetDesignOrder.id = :carpetDesignOrderId')
            ->setParameter('vignette', 'Vignette')
            ->setParameter('carpetDesignOrderId', $carpetDesignOrderId);
        // Execute the query and fetch the count
        try {
            $count = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException) {
            $count = 0;
        }

        // Return true if count is greater than 0, otherwise false
        return $count > 0;
    }

    public function getCarpetDesignOrderOptions(QuoteDetail $quoteDetail): array
    {
        $quote = $quoteDetail->getQuote();
        return $this->manager->createQueryBuilder()
            ->select('cdo')
            ->from(self::ENTITY_CLASS, 'cdo')
            ->join('cdo.projectDi', 'pd')
            ->join('pd.contremarque', 'c')
            ->where('c = :contremarque')
            ->andWhere('cdo.location = :emplacement')
            ->setParameter('contremarque', $quote->getContremarque())
            ->setParameter('emplacement', $quoteDetail->getLocation())
            ->getQuery()
            ->getResult();
    }


    public function getImageTypeNames(int $carpetId): array
    {
        return $this->manager->createQueryBuilder()
            ->select('it.name')
            ->from(Image::class, 'i')
            ->join('i.imageType', 'it')
            ->where('i.carpetDesignOrder = :carpetDesignOrderId')
            ->setParameter('carpetDesignOrderId', $carpetId)
            ->getQuery()
            ->getScalarResult();
    }

    public function hasAssociatedCarpetDesignOrders(Location $location): bool
    {
        $count = $this->manager->createQueryBuilder('carpetDesignOrder')
            ->from(self::ENTITY_CLASS, 'carpetDesignOrder')
            ->select('COUNT(carpetDesignOrder.id)')
            ->andWhere('carpetDesignOrder.location = :location')
            ->setParameter('location', $location)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    public function createQueryBuilderForFixture(string $alias)
    {
        return $this->manager->createQueryBuilder()
            ->select($alias)
            ->from(self::ENTITY_CLASS, $alias)
            ->leftJoin("$alias.images", 'i'); // Join with images for the SIZE check
    }
}
