<?php

declare(strict_types=1);

namespace App\Contact\Repository\ORM\Doctrine;

use App\Common\Exception\ValidationException;
use App\Common\Repository\ORM\Doctrine\DoctrineORMRepository;
use App\Contact\Entity\CustomerIntermediaryHistory;
use App\Contact\Repository\CustomerIntermediaryHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMCustomerIntermediaryHistoryRepository extends DoctrineORMRepository implements CustomerIntermediaryHistoryRepository
{
    protected const ENTITY_CLASS = CustomerIntermediaryHistory::class;
    protected const ALIAS = 'customerIntermediaryHistory';

    /**
     * DoctrineORMCustomerIntermediaryHistoryRepository constructor.
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

    public function save(CustomerIntermediaryHistory $customerIntermediaryHistory, $flush = false): void
    {
        if ($this->checkOverlap($customerIntermediaryHistory)) {
            throw new ValidationException(['New entry has overlapping validity period with existing entries.']);
        }

        $this->manager->persist($customerIntermediaryHistory);
        if ($flush) {
            $this->manager->flush();
        }
    }

    private function hasOverlap(CustomerIntermediaryHistory $newEntry, CustomerIntermediaryHistory $existingEntry): bool
    {
        $newFromDate = $newEntry->getDateFrom();
        $newToDate = $newEntry->getDateTo();

        $existingFromDate = $existingEntry->getDateFrom();
        $existingToDate = $existingEntry->getDateTo();

        return
            ($newFromDate >= $existingFromDate && $newFromDate <= $existingToDate)
            || ($newToDate >= $existingFromDate && $newToDate <= $existingToDate)
            || ($existingFromDate >= $newFromDate && $existingToDate <= $newToDate)
        ;
    }

    private function checkOverlap(CustomerIntermediaryHistory $customerIntermediaryHistory): bool
    {
        $existingEntries = $this->findBy([
            'customer' => $customerIntermediaryHistory->getCustomer(),
            'intermediary' => $customerIntermediaryHistory->getIntermediary(),
        ]);
        $hasOverLap = false;
        foreach ($existingEntries as $existingEntry) {
            if ($this->hasOverlap($customerIntermediaryHistory, $existingEntry)) {
                $hasOverLap = true;
                break;
            }
        }

        return $hasOverLap;
    }
}
