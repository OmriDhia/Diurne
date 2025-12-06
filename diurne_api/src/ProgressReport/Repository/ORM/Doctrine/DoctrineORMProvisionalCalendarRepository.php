<?php

namespace App\ProgressReport\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\ProgressReport\Entity\ProvisionalCalendar;
use App\ProgressReport\Repository\ProvisionalCalendarRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMProvisionalCalendarRepository extends DoctrineORMRepository implements ProvisionalCalendarRepository
{
    protected const ENTITY_CLASS = ProvisionalCalendar::class;
    protected const ALIAS = 'provisionalCalendar';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(ProvisionalCalendar $calendar, bool $flush = false): void
    {
        $this->persist($calendar);
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

