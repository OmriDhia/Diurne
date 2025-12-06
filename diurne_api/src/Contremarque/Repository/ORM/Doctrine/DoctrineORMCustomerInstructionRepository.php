<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\CustomerInstruction;
use App\Contremarque\Repository\CustomerInstructionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCustomerInstructionRepository extends DoctrineORMRepository implements CustomerInstructionRepository
{
    protected const ENTITY_CLASS = CustomerInstruction::class;
    protected const ALIAS = 'customerInstruction';

    /**
     * DoctrineORMCustomerInstructionRepository constructor.
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

    public function findOneById(int $id): ?CustomerInstruction
    {
        return $this->find((int)$id);
    }

    public function findByCarpetDesignOrder($id)
    {
        return $this->manager->createQueryBuilder()
            ->select('customerInstruction')
            ->from(self::ENTITY_CLASS, self::ALIAS)
            ->where('customerInstruction.objectId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
