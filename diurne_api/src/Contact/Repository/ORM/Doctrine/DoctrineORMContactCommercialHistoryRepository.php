<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use Exception;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Repository\ContactCommercialHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMContactCommercialHistoryRepository extends DoctrineORMRepository implements ContactCommercialHistoryRepository
{
    protected const ENTITY_CLASS = ContactCommercialHistory::class;
    protected const ALIAS = 'contactCommercialHistory';

    /**
     * DoctrineORMContactCommercialHistoryRepository constructor.
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
        // TODO: Implement create() method.
    }

    /**
     * @return void
     */
    public function update($entity, array $data)
    {
        // TODO: Implement update() method.
    }

    public function save(ContactCommercialHistory $contactCommercialHistory, $flush = false): void
    {
        if (!$contactCommercialHistory->getId() && $this->checkOverlap($contactCommercialHistory)) {
            throw new Exception('New entry has overlapping validity period with existing entries.');
        }

        $this->manager->persist($contactCommercialHistory);
        if ($flush) {
            $this->manager->flush();
        }
    }

    private function hasOverlap(ContactCommercialHistory $newEntry, ContactCommercialHistory $existingEntry): bool
    {
        $newFromDate = $newEntry->getFromDate();
        $newToDate = $newEntry->getToDate();

        $existingFromDate = $existingEntry->getFromDate();
        $existingToDate = $existingEntry->getToDate();

        return
            ($newFromDate >= $existingFromDate && $newFromDate <= $existingToDate)
            || ($newToDate >= $existingFromDate && $newToDate <= $existingToDate)
            || ($existingFromDate >= $newFromDate && $existingToDate <= $newToDate)
        ;
    }

    private function checkOverlap(ContactCommercialHistory $contactCommercialHistory): bool
    {
        $existingEntries = $this->findBy([
            'customer' => $contactCommercialHistory->getCustomer(),
            'commercial' => $contactCommercialHistory->getCommercial(),
        ]);
        $hasOverLap = false;
        foreach ($existingEntries as $existingEntry) {
            if ($this->hasOverlap($contactCommercialHistory, $existingEntry)) {
                $hasOverLap = true;
                break;
            }
        }

        return $hasOverLap;
    }

    public function findLast($customer): object|null
    {
        return $this->findOneBy([
            'customer' => $customer,
        ]);
    }

    public function findSecondToLastByCustomerId(int $customerId): ?ContactCommercialHistory
    {
        $qb = $this->query();
        $qb->where('contactCommercialHistory.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->orderBy('contactCommercialHistory.id', 'DESC')
            ->setMaxResults(2);

        $result = $qb->getQuery()->getResult();

        return $result[1] ?? null;
    }
}
