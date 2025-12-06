<?php

namespace App\CheckingList\Repository\ORM;

use App\CheckingList\Entity\LayersValidation;
use App\CheckingList\Repository\LayersValidationRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMLayersValidationRepository extends DoctrineORMRepository implements LayersValidationRepository
{
    protected const ENTITY_CLASS = LayersValidation::class;
    protected const ALIAS = 'layersValidation';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(LayersValidation $entity, bool $flush = false): void
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
