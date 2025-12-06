<?php

namespace App\ProgressReport\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\ProgressReport\Entity\ProgressReport;
use App\ProgressReport\Repository\ProgressReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProgressReportRepository extends DoctrineORMRepository implements ProgressReportRepository
{
    protected const ENTITY_CLASS = ProgressReport::class;
    protected const ALIAS = 'progressReport';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ProgressReport $progressReport, bool $flush = false): void
    {
        $this->persist($progressReport);
        if ($flush) {
            $this->flush();
        }
    }

    public function create(array $data): void
    {
        // not implemented
    }

    public function update($entity, array $data): void
    {
        // not implemented
    }
}

