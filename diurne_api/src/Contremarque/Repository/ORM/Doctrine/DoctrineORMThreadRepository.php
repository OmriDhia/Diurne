<?php

declare(strict_types=1);

namespace App\Contremarque\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contremarque\Entity\Thread;
use App\Contremarque\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMThreadRepository extends DoctrineORMRepository implements ThreadRepository
{
    protected const ENTITY_CLASS = Thread::class;
    protected const ALIAS = 'thread';

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    public function save(Thread $thread, $flush = false): void
    {
        $this->manager->persist($thread);

        if ($flush) {
            $this->manager->flush();
        }
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
}
