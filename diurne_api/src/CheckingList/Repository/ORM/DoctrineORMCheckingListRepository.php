<?php

namespace App\CheckingList\Repository\ORM;

use App\CheckingList\Entity\CheckingList;
use App\CheckingList\Repository\CheckingListRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCheckingListRepository extends DoctrineORMRepository implements CheckingListRepository
{

    protected const ENTITY_CLASS = CheckingList::class;
    protected const ALIAS = 'checkingList';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(CheckingList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data): void
    {
        // TODO: Implement update() method.
    }

}