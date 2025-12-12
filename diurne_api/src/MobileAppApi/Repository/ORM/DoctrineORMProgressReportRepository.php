<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository\ORM;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\MobileAppApi\Entity\ProgressReport;
use App\MobileAppApi\Repository\ProgressReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProgressReportRepository extends DoctrineORMRepository implements ProgressReportRepository
{
    protected const ENTITY_CLASS = ProgressReport::class;
    protected const ALIAS = 'progressReport';

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

    public function save(ProgressReport $entity, bool $flush = false): void
    {
        $this->persist($entity);
        if ($flush) {
            $this->flush();
        }
    }
}
