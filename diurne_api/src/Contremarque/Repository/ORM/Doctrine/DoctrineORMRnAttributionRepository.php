<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;


use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Repository\RnAttributionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMRnAttributionRepository extends DoctrineORMRepository implements RnAttributionRepository
{
    protected const ENTITY_CLASS = RnAttribution::class;
    protected const ALIAS = 'rnAttribution';


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

    public function findLastCanceledByCarpetOrderDetail(CarpetOrderDetail $carpetOrderDetail): ?RnAttribution
    {
        $qb = $this->manager->createQueryBuilder(self::ALIAS);

        return $qb
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->andWhere(self::ALIAS . '.carpetOrderDetail = :carpetOrderDetail')
            ->andWhere(self::ALIAS . '.canceledAt IS NOT NULL')
            ->setParameter('carpetOrderDetail', $carpetOrderDetail)
            ->orderBy(self::ALIAS . '.canceledAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
