<?php

namespace App\CheckingList\Repository\ORM;


use App\CheckingList\Entity\QualityRespect;
use App\CheckingList\Repository\QualityRespectRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMQualityRespectRepository extends DoctrineORMRepository implements QualityRespectRepository
{

    protected const ENTITY_CLASS = QualityRespect::class;
    protected const ALIAS = 'qualityRespect';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(QualityRespect $entity, bool $flush = false): void
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