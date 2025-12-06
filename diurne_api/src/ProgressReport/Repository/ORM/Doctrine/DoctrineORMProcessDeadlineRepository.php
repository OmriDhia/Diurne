<?php

declare(strict_types=1);

namespace App\ProgressReport\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\ProgressReport\Entity\ProcessDeadline;
use App\ProgressReport\Repository\ProcessDeadlineRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProcessDeadlineRepository extends DoctrineORMRepository implements ProcessDeadlineRepository
{
    protected const ENTITY_CLASS = ProcessDeadline::class;
    protected const ALIAS = 'processDeadline';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ProcessDeadline $deadline, bool $flush = false): void
    {
        $this->persist($deadline);
        if ($flush) {
            $this->flush();
        }
    }

    public function create(array $data): void {}
    public function update($entity, array $data): void {}
}
