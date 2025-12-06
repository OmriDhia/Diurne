<?php

namespace App\ProgressReport\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\ProgressReport\Entity\ProgressReportStatus;
use App\ProgressReport\Repository\ProgressReportStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProgressReportStatusRepository extends DoctrineORMRepository implements ProgressReportStatusRepository
{
    protected const ENTITY_CLASS = ProgressReportStatus::class;
    protected const ALIAS = 'progressReportStatus';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ProgressReportStatus $status, bool $flush = false): void
    {
        $this->persist($status);
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

