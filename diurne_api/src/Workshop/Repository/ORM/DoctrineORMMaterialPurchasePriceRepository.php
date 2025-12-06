<?php
declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\MaterialPurchasePrice;
use App\Workshop\Repository\MaterialPurchasePriceRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMMaterialPurchasePriceRepository extends DoctrineORMRepository implements MaterialPurchasePriceRepository
{
    protected const ENTITY_CLASS = MaterialPurchasePrice::class;
    protected const ALIAS = 'materialPurchasePrice';

    /**
     * @param EntityManagerInterface $registry
     */
    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @param MaterialPurchasePrice $entity
     * @param bool $flush
     * @return void
     */
    public function save(MaterialPurchasePrice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $entity
     * @param array $data
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

}