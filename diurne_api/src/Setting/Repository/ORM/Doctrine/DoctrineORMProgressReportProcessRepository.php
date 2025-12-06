<?php

declare(strict_types=1);

namespace App\Setting\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Setting\Entity\ProgressReportProcess;
use App\Setting\Repository\ProgressReportProcessRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProgressReportProcessRepository extends DoctrineORMRepository implements ProgressReportProcessRepository
{
    protected const ENTITY_CLASS = ProgressReportProcess::class;
    protected const ALIAS = 'progressReportProcess';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ProgressReportProcess $process, bool $flush = false): void
    {
        $this->persist($process);
        if ($flush) {
            $this->flush();
        }
    }

    public function create(array $data): void {}
    public function update($entity, array $data): void {}
}
