<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact;

use LogicException;
use Exception;
use DateTimeInterface;
use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\Customer;
use App\Contact\Entity\CustomerIntermediaryHistory;
use App\Contact\Entity\IntermediaryType;
use App\Contact\Repository\CustomerRepository;
use App\Contact\Repository\IntermediaryTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final readonly class AssignIntermediaryToCustomerCommandHandler implements CommandHandler
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private IntermediaryTypeRepository $intermediaryTypeRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    public function __invoke(AssignIntermediaryToCustomerCommand $command): AssignIntermediaryToCustomerResponse
    {
        $this->entityManager->beginTransaction();

        try {
            // Fetch and validate entities
            $customer = $this->fetchCustomer($command->customerId);
            $intermediary = $this->fetchIntermediary($command->intermediaryId);
            $intermediaryType = $this->fetchIntermediaryType($command->intermediaryTypeId);

            // Validate that the intermediary is actually an intermediary
            if (!$intermediary->isIntermediary()) {
                throw new LogicException("Customer with ID {$command->intermediaryId} is not marked as an intermediary.");
            }

            $intermediaryInfoSheet = $intermediary->getIntermediaryInformationSheet();
            if (!$intermediaryInfoSheet) {
                throw new LogicException("Intermediary with ID {$command->intermediaryId} does not have an intermediary information sheet.");
            }

            // Validate that the provided intermediaryTypeId matches the intermediary's type
            if ($intermediaryInfoSheet->getIntermediaryType()->getId() !== $intermediaryType->getId()) {
                throw new LogicException("The provided intermediary type ID {$command->intermediaryTypeId} does not match the intermediary's type.");
            }

            // Check for overlapping date ranges in existing CustomerIntermediaryHistory records
            $this->checkForOverlappingDates(
                $customer,
                $intermediary,
                $command->fromDate,
                $command->toDate
            );

            // Close existing intermediary assignments by setting their dateTo to today
            $this->closeExistingAssignments($customer, $command->fromDate);

            // Create the new relationship history
            $customerIntermediaryHistory = new CustomerIntermediaryHistory();
            $customerIntermediaryHistory
                ->setCustomer($customer)
                ->setIntermediary($intermediary)
                ->setDateFrom($command->fromDate)
                ->setDateTo($command->toDate);

            $this->entityManager->persist($customerIntermediaryHistory);
            $this->entityManager->flush();

            $this->entityManager->commit();

            $this->logger->info('Intermediary assigned to customer', [
                'customerId' => $command->customerId,
                'intermediaryId' => $command->intermediaryId,
                'fromDate' => $command->fromDate->format('Y-m-d H:i:s'),
                'toDate' => $command->toDate?->format('Y-m-d H:i:s'),
            ]);

            return new AssignIntermediaryToCustomerResponse($customer, $intermediary, $customerIntermediaryHistory);
        } catch (Exception $e) {
            $this->entityManager->rollback();
            $this->logger->error('Failed to assign intermediary to customer', [
                'customerId' => $command->customerId,
                'intermediaryId' => $command->intermediaryId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function fetchCustomer(int $customerId): Customer
    {
        $customer = $this->customerRepository->find($customerId);
        if (!$customer instanceof Customer) {
            throw new ResourceNotFoundException("Customer with ID {$customerId} not found.");
        }
        return $customer;
    }

    private function fetchIntermediary(int $intermediaryId): Customer
    {
        $intermediary = $this->customerRepository->find($intermediaryId);
        if (!$intermediary instanceof Customer) {
            throw new ResourceNotFoundException("Intermediary (Customer) with ID {$intermediaryId} not found.");
        }
        return $intermediary;
    }

    private function fetchIntermediaryType(int $intermediaryTypeId): IntermediaryType
    {
        $intermediaryType = $this->intermediaryTypeRepository->find($intermediaryTypeId);
        if (!$intermediaryType instanceof IntermediaryType) {
            throw new ResourceNotFoundException("Intermediary type with ID {$intermediaryTypeId} not found.");
        }
        return $intermediaryType;
    }

    private function checkForOverlappingDates(
        Customer $customer,
        Customer $intermediary,
        DateTimeInterface $fromDate,
        ?DateTimeInterface $toDate
    ): void {
        $histories = $customer->getCustomerIntermediaryHistories();

        foreach ($histories as $history) {
            if ($history->getIntermediary()->getId() !== $intermediary->getId()) {
                continue; // Skip if it's not the same intermediary
            }

            $existingFromDate = $history->getDateFrom();
            $existingToDate = $history->getDateTo();

            // If the new range has no end date, or the existing range has no end date, they overlap
            if ($toDate === null || $existingToDate === null) {
                if ($existingFromDate <= $fromDate || $fromDate <= $existingToDate) {
                    throw new LogicException('The new date range overlaps with an existing intermediary assignment.');
                }
            }

            // Check if the new range overlaps with the existing range
            if (
                ($fromDate <= $existingToDate && $toDate >= $existingFromDate) ||
                ($toDate === null && $fromDate <= $existingToDate)
            ) {
                throw new LogicException('The new date range overlaps with an existing intermediary assignment.');
            }
        }
    }

    private function closeExistingAssignments(Customer $customer, DateTimeInterface $newFromDate): void
    {
        $today = new DateTimeImmutable();
        $histories = $customer->getCustomerIntermediaryHistories();

        foreach ($histories as $history) {
            // Skip if the history already has a dateTo that is in the past
            if ($history->getDateTo() !== null && $history->getDateTo() <= $today) {
                continue;
            }

            // Skip if the history's dateFrom is after the new assignment's dateFrom
            if ($history->getDateFrom() > $newFromDate) {
                continue;
            }

            // Set dateTo to today
            $history->setDateTo($today);
            $this->entityManager->persist($history);

            $this->logger->info('Closed existing intermediary assignment', [
                'customerId' => $customer->getId(),
                'intermediaryId' => $history->getIntermediary()->getId(),
                'historyId' => $history->getId(),
                'dateTo' => $today->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
