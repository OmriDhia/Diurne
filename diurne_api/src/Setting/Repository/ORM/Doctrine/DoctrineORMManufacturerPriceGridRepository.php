<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class DoctrineORMManufacturerPriceGridRepository extends DoctrineORMRepository implements ManufacturerPriceGridRepository
{
    protected const ENTITY_CLASS = ManufacturerPriceGrid::class;
    protected const ALIAS = 'mpg';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): object
    {
        $priceGrid = new ManufacturerPriceGrid();

        if (isset($data['manufacturer'])) {
            $priceGrid->setManufacturer($data['manufacturer']);
        }

        if (isset($data['quality'])) {
            $priceGrid->setQuality($data['quality']);
        }

        if (isset($data['tarifGroup'])) {
            $priceGrid->setTarifGroup($data['tarifGroup']);
        }

        if (isset($data['tariffGrid'])) {
            $priceGrid->setTariffGrid($data['tariffGrid']);
        }

        if (isset($data['knots'])) {
            $priceGrid->setKnots($data['knots']);
        }

        if (isset($data['special'])) {
            $priceGrid->setSpecial($data['special']);
        }

        if (isset($data['standardVelours'])) {
            $priceGrid->setStandardVelours($data['standardVelours']);
        }

        if (isset($data['isActive'])) {
            $priceGrid->setIsActive($data['isActive']);
        }

        $this->manager->persist($priceGrid);

        if (($data['flush'] ?? false) === true) {
            $this->manager->flush();
        }

        return $priceGrid;
    }

    /**
     * {@inheritdoc}
     */
    public function update($entity, array $data): void
    {
        if (!$entity instanceof ManufacturerPriceGrid) {
            throw new \InvalidArgumentException('Entity must be an instance of ManufacturerPriceGrid');
        }

        if (isset($data['manufacturer'])) {
            $entity->setManufacturer($data['manufacturer']);
        }

        if (isset($data['quality'])) {
            $entity->setQuality($data['quality']);
        }

        if (isset($data['tarifGroup'])) {
            $entity->setTarifGroup($data['tarifGroup']);
        }

        if (isset($data['tariffGrid'])) {
            $entity->setTariffGrid($data['tariffGrid']);
        }

        if (isset($data['knots'])) {
            $entity->setKnots($data['knots']);
        }

        if (isset($data['special'])) {
            $entity->setSpecial($data['special']);
        }

        if (isset($data['standardVelours'])) {
            $entity->setStandardVelours($data['standardVelours']);
        }

        if (isset($data['isActive'])) {
            $entity->setIsActive($data['isActive']);
        }

        $this->manager->persist($entity);

        if (($data['flush'] ?? false) === true) {
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(object $entity, bool $flush = false): void
    {
        $this->manager->persist($entity);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findByManufacturerAndTarifGroup(int $manufacturerId, int $tarifGroupId): array
    {
        $qb = $this->query();

        return $qb
            ->leftJoin('mpg.manufacturer', 'm')
            ->leftJoin('mpg.quality', 'q')
            ->leftJoin('mpg.tarifGroup', 'tg')
            ->where('m.id = :manufacturerId')
            ->andWhere('tg.id = :tarifGroupId')
            ->andWhere('mpg.isActive = true')
            ->setParameter('manufacturerId', $manufacturerId)
            ->setParameter('tarifGroupId', $tarifGroupId)
            ->orderBy('q.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableTarifGroupsForManufacturer(int $manufacturerId): array
    {
        $qb = $this->query();

        return $qb
            ->select('DISTINCT tg')
            ->leftJoin('mpg.tarifGroup', 'tg')
            ->where('mpg.manufacturer = :manufacturerId')
            ->setParameter('manufacturerId', $manufacturerId)
            ->orderBy('tg.year', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByManufacturerQualityAndTarifGroup(int $manufacturerId, int $qualityId, int $tarifGroupId): ?object
    {
        $qb = $this->query();

        try {
            return $qb
                ->where('mpg.manufacturer = :manufacturerId')
                ->andWhere('mpg.quality = :qualityId')
                ->andWhere('mpg.tarifGroup = :tarifGroupId')
                ->setParameter('manufacturerId', $manufacturerId)
                ->setParameter('qualityId', $qualityId)
                ->setParameter('tarifGroupId', $tarifGroupId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findPriceHistory(int $manufacturerId, int $qualityId, ?int $limit = null): array
    {
        $qb = $this->query();

        $qb
            ->where('mpg.manufacturer = :manufacturerId')
            ->andWhere('mpg.quality = :qualityId')
            ->setParameter('manufacturerId', $manufacturerId)
            ->setParameter('qualityId', $qualityId)
            ->leftJoin('mpg.tarifGroup', 'tg')
            ->orderBy('tg.year', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function deactivateAllForManufacturerAndTarifGroup(int $manufacturerId, int $tarifGroupId): int
    {
        $qb = $this->manager->createQueryBuilder();

        return $qb
            ->update(self::ENTITY_CLASS, 'mpg')
            ->set('mpg.isActive', ':active')
            ->where('mpg.manufacturer = :manufacturerId')
            ->andWhere('mpg.tarifGroup = :tarifGroupId')
            ->setParameter('active', false)
            ->setParameter('manufacturerId', $manufacturerId)
            ->setParameter('tarifGroupId', $tarifGroupId)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderWithFilters(array $filters = []): QueryBuilder
    {
        $qb = $this->query();

        if (isset($filters['manufacturerId'])) {
            $qb->andWhere('mpg.manufacturer = :manufacturerId')
                ->setParameter('manufacturerId', $filters['manufacturerId']);
        }

        if (isset($filters['tarifGroupId'])) {
            $qb->andWhere('mpg.tarifGroup = :tarifGroupId')
                ->setParameter('tarifGroupId', $filters['tarifGroupId']);
        }

        if (isset($filters['qualityId'])) {
            $qb->andWhere('mpg.quality = :qualityId')
                ->setParameter('qualityId', $filters['qualityId']);
        }

        if (isset($filters['isActive'])) {
            $qb->andWhere('mpg.isActive = :isActive')
                ->setParameter('isActive', $filters['isActive']);
        }

        // Tri par défaut
        if (!isset($filters['orderBy'])) {
            $qb->leftJoin('mpg.tarifGroup', 'tg_order')
                ->orderBy('tg_order.year', 'DESC')
                ->addOrderBy('mpg.quality', 'ASC');
        }

        return $qb;
    }

    /**
     * Compte le nombre de grilles selon des critères
     */
    public function countByCriteria(array $criteria): int
    {
        $qb = $this->query()
            ->select('COUNT(mpg.id)');

        if (isset($criteria['manufacturer'])) {
            $qb->andWhere('mpg.manufacturer = :manufacturer')
                ->setParameter('manufacturer', $criteria['manufacturer']);
        }

        if (isset($criteria['tarifGroup'])) {
            $qb->andWhere('mpg.tarifGroup = :tarifGroup')
                ->setParameter('tarifGroup', $criteria['tarifGroup']);
        }

        if (isset($criteria['isActive'])) {
            $qb->andWhere('mpg.isActive = :isActive')
                ->setParameter('isActive', $criteria['isActive']);
        }

        try {
            return (int) $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException) {
            return 0;
        }
    }
    /**
     * {@inheritdoc}
     */
    public function findAvailableTarifGroups(): array
    {
        $qb = $this->query();

        $result = $qb
            ->select('DISTINCT tg')
            ->leftJoin('mpg.tarifGroup', 'tg')
            ->where('mpg.isActive = true')
            ->orderBy('tg.year', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(fn($item) => $item['tg'] ?? $item, $result);
    }
}
