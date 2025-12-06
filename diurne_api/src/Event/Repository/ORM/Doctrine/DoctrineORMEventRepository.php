<?php

declare(strict_types=1);

namespace App\Event\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Event\Entity\Event;
use App\Event\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMEventRepository extends DoctrineORMRepository implements EventRepository
{
    protected const ENTITY_CLASS = Event::class;
    protected const ALIAS = 'event';

    /**
     * DoctrineORMEventRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data)
    {
    }

    /**
     * @return void
     */
    public function update($event, array $data)
    {
    }
}
