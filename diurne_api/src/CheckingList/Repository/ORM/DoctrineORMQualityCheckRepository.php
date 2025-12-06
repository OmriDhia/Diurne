<?php

namespace App\CheckingList\Repository\ORM;

use App\CheckingList\Entity\QualityCheck;
use App\CheckingList\Repository\QualityCheckRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMQualityCheckRepository extends DoctrineORMRepository implements QualityCheckRepository
{

    protected const ENTITY_CLASS = QualityCheck::class;
    protected const ALIAS = 'qualityCheck';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(QualityCheck $entity, bool $flush = false): void
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