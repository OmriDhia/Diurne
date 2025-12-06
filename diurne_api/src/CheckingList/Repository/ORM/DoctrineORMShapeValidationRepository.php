<?php

namespace App\CheckingList\Repository\ORM;

use App\CheckingList\Entity\ShapeValidation;
use App\CheckingList\Repository\ShapeValidationRepository;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMShapeValidationRepository extends DoctrineORMRepository implements ShapeValidationRepository
{
    protected const ENTITY_CLASS = ShapeValidation::class;
    protected const ALIAS = 'shapeValidation';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ShapeValidation $entity, bool $flush = false): void
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
