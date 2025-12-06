<?php

declare(strict_types=1);

namespace App\Workshop\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Workshop\Entity\WorkshopInformation;
use App\Workshop\Repository\WorkshopInformationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class DoctrineORMWorkshopInformationRepository extends DoctrineORMRepository implements WorkshopInformationRepository
{
    protected const ENTITY_CLASS = WorkshopInformation::class;
    protected const ALIAS = 'wi';

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

    public function save(WorkshopInformation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return MaterialPurchasePrice[]
     */
    public function findByWorkshopInformation(WorkshopInformation $workshopInformation): array
    {
        return $this->findBy(['workshopInformation' => $workshopInformation]);
    }
}
