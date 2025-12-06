<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use DateTime;
use Exception;
use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Entity\Customer;
use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\ContactCommercialHistoryRepository;
use App\Contact\Repository\CustomerRepository;
use App\User\Entity\User;
use App\User\Repository\UserRepository;

class AssignCommercialToCustomerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepository                     $userRepository,
        private readonly CustomerRepository                 $customerRepository,
        private readonly ContactCommercialHistoryRepository $contactCommercialHistoryRepository,
        private readonly AttributionStatusRepository        $attributionStatusRepository
    )
    {
    }

    public function __invoke(AssignCommercialToCustomerCommand $command): AssignCommercialToCustomerResponse
    {
        $user = $this->userRepository->find((int)$command->getCommercialId());

        if (!$user instanceof User) {
            throw new ResourceNotFoundException();
        }
        /**
         * @var Customer
         */
        $customer = $this->customerRepository->find((int)$command->getCustomerId());

        if (!$customer instanceof Customer) {
            throw new ResourceNotFoundException();
        }
        $to = null;
        try {
            $from = new DateTime($command->getFromDate());
            if (!empty($command->getToDate())) {
                $to = new DateTime($command->getToDate());
            }
        } catch (Exception) {
            throw new InvalidArgumentException('Invalid date format.');
        }

        if (empty($command->getFromDate())) {
            $from = new DateTime();
        }

        if (!empty($to) && $from >= $to) {
            throw new ValidationException(['The "From" date must be earlier than the "To" date.']);
        }
        $lastRow = $this->contactCommercialHistoryRepository->findLast($customer);
        if ($lastRow !== null) {
            $lastRow->setToDate($from);
            $this->contactCommercialHistoryRepository->save($lastRow, true);
        }

        $contactCommercialHistory = $this->contactCommercialHistoryRepository->findOneBy(
            [
                'commercial' => $user,
                'customer' => $customer,
            ]
        );

        if (!$contactCommercialHistory instanceof ContactCommercialHistory) {
            $contactCommercialHistory = new ContactCommercialHistory();
        }

        $status = $command->getStatus();
        if (empty($status)) {
            $status = 'Pending';
        }
        $status = $this->attributionStatusRepository->findOneByName($status);
        $contactCommercialHistory->setStatus($status);
        $contactCommercialHistory->setCommercial($user);
        $contactCommercialHistory->setCustomer($customer);
        $contactCommercialHistory->setFromDate($from);

        $contactCommercialHistory->setToDate($to);
        $this->contactCommercialHistoryRepository->save($contactCommercialHistory, true);

        return new AssignCommercialToCustomerResponse($customer, $user);
    }
}
