<?php

declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Entity\WorkshopInformationMaterial;
use App\Workshop\Repository\WorkshopInformationMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMWorkshopInformationMaterialRepository extends DoctrineORMRepository implements WorkshopInformationMaterialRepository
{
    protected const ENTITY_CLASS = WorkshopInformationMaterial::class;
    protected const ALIAS = 'workshopInformationMaterial';

    public function __construct(EntityManagerInterface $registry)
    {
        parent::__construct($registry, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(WorkshopInformationMaterial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByWorkshopInformation(WorkshopInformation $workshopInformation): array
    {
        return $this->findBy(['workshopInformation' => $workshopInformation]);
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }
}
