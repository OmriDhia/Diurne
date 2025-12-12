<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\Workshop;
use App\MobileAppApi\Repository\WorkshopRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMWorkshopRepository extends DoctrineORMRepository implements WorkshopRepository
{
    protected const ENTITY_CLASS = Workshop::class;
    protected const ALIAS = 'workshop';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function create(array $data): void
    {
        // TODO: Implement create() method.
    }

    public function update($entity, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function save(Workshop $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
