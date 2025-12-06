<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use Doctrine\ORM\NoResultException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Entity\TarifGroup;
use App\Setting\Repository\CollectionGroupPriceRepository;
use App\Setting\Repository\CollectionGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCollectionGroupPriceRepository extends DoctrineORMRepository implements CollectionGroupPriceRepository
{
    protected const ENTITY_CLASS = CollectionGroupPrice::class;
    protected const ALIAS = 'collectionGroupPrice';

    public function __construct(
        EntityManagerInterface $manager,
        private readonly CollectionGroupRepository $collectionGroupRepository
    ) {
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

    private function findPrice(
        CollectionGroup $collectionGroup,
        TarifGroup $tarifGroup,
        string $priceField,
        ?bool $useUpperGroup = false
    ): ?string {
        if ($useUpperGroup) {
            return $this->findNextGroupPrice($collectionGroup, $tarifGroup, $priceField);
        }

        try {
            $qb = $this->manager->createQueryBuilder();
            $qb->from(self::ENTITY_CLASS, self::ALIAS)
                ->select(self::ALIAS . '.' . $priceField)
                ->where(self::ALIAS . '.collectionGroup = :collectionGroup')
                ->andWhere(self::ALIAS . '.tarifGroup = :tarifGroup')
                ->setParameter('collectionGroup', $collectionGroup)
                ->setParameter('tarifGroup', $tarifGroup);

            $price = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException) {
            $price = (string) 0;
        }

        if ($price === '0') {
            return $this->findNextGroupPrice($collectionGroup, $tarifGroup, $priceField);
        }

        return $price;
    }

    /**
     * Finds the price for the next collection group in sequence.
     *
     * @param CollectionGroup $collectionGroup The current collection group.
     * @param TarifGroup $tarifGroup The tarif group associated with the collection group.
     * @param string $priceField The field name for the price to retrieve.
     * 
     * @return ?string The price of the next collection group if it exists, otherwise '0'.
     */

    private function findNextGroupPrice(
        CollectionGroup $collectionGroup,
        TarifGroup $tarifGroup,
        string $priceField
    ): ?string {
        $nextCollectionGroup = $this->collectionGroupRepository
            ->find($collectionGroup->getId() + 1);

        if ($nextCollectionGroup) {
            return $this->findPrice($nextCollectionGroup, $tarifGroup, $priceField);
        }

        return (string) 0;
    }

    public function findMinPrice(CollectionGroup $collectionGroup, TarifGroup $tarifGroup, ?bool $useUpperGroup = false): ?string
    {
        return $this->findPrice($collectionGroup, $tarifGroup, 'price', $useUpperGroup);
    }

    public function findMaxPrice(CollectionGroup $collectionGroup, TarifGroup $tarifGroup, ?bool $useUpperGroup = false): ?string
    {
        return $this->findPrice($collectionGroup, $tarifGroup, 'price_max', $useUpperGroup);
    }
}
