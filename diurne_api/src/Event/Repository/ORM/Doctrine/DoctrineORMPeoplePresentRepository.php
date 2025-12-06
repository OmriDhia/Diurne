<?php

declare(strict_types=1);

namespace App\Event\Repository\ORM\Doctrine;

use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Event\Entity\PeoplePresent;
use App\Event\Repository\PeoplePresentRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMPeoplePresentRepository extends DoctrineORMRepository implements PeoplePresentRepository
{
    protected const ENTITY_CLASS = PeoplePresent::class;
    protected const ALIAS = 'peoplePresent';

    /**
     * DoctrineORMPeoplePresentRepository constructor.
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return void
     */
    public function create(array $data) {}

    /**
     * @return void
     */
    public function update($peoplePresent, array $data) {}

    /**
     * Delete all PeoplePresent records associated with a specific event.
     */
    public function deleteByEventId(int $eventId): void
    {
        $qb = $this->manager->createQueryBuilder();
        $qb->delete(PeoplePresent::class, self::ALIAS)
            ->where($qb->expr()->eq(self::ALIAS . '.event', ':eventId'))
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->execute();
    }
    /**
     * Find all PeoplePresent records associated with the given event IDs.
     *
     * @param int[] $eventIds
     * @return PeoplePresent[]
     */
    public function findByEventIds(array $eventIds): array
    {
        if (empty($eventIds)) {
            return [];
        }

        $qb = $this->manager->createQueryBuilder();
        $qb->select(self::ALIAS)
            ->from(PeoplePresent::class, self::ALIAS)
            ->where($qb->expr()->in(self::ALIAS . '.event', ':eventIds'))
            ->setParameter('eventIds', $eventIds);

        return $qb->getQuery()->getResult();
    }
}
