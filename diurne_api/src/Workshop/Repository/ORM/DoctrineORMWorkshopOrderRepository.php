<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\WorkshopOrder;
use App\Workshop\Repository\WorkshopOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class DoctrineORMWorkshopOrderRepository extends DoctrineORMRepository implements WorkshopOrderRepository
{
    protected const ENTITY_CLASS = WorkshopOrder::class;
    protected const ALIAS = 'workshopOrder';

    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(WorkshopOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
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


    public function findByCriteria(
        array  $criteria = [],
        ?array $orderBy = null,
        ?int   $limit = null,
        ?int   $offset = null
    ): Paginator
    {
        $qb = $this->manager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from(self::ENTITY_CLASS, self::ALIAS)
            // Join with imageCommand
            ->leftJoin(self::ALIAS . '.imageCommand', 'imageCommand')
            // Join with carpetDesignOrder through imageCommand
            ->leftJoin('imageCommand.carpetDesignOrder', 'carpetDesignOrder')
            // Join with location through carpetDesignOrder
            ->leftJoin('carpetDesignOrder.location', 'location');

        foreach ($criteria as $field => $value) {
            if ($value !== null) {
                // Handle special filters differently
                switch ($field) {
                    case 'customerId':
                        $qb->andWhere('carpetDesignOrder.projectDi = :customerId')
                            ->setParameter('customerId', $value);
                        break;
                    case 'contremarqueId':
                        $qb->andWhere('location.contremarque = :contremarqueId')
                            ->setParameter('contremarqueId', $value);
                        break;
                    default:
                        // Handle regular fields on the WorkshopOrder entity
                        $qb->andWhere(self::ALIAS . ".{$field} = :{$field}")
                            ->setParameter($field, $value);
                        break;
                }
            }
        }

        if ($orderBy) {
            foreach ($orderBy as $field => $direction) {
                // Handle ordering on joined fields if needed
                if (str_contains($field, '.')) {
                    $qb->addOrderBy($field, $direction);
                } else {
                    $qb->addOrderBy(self::ALIAS . ".{$field}", $direction);
                }
            }
        }

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        if ($offset !== null) {
            $qb->setFirstResult($offset);
        }

        return new Paginator($qb->getQuery());
    }
}