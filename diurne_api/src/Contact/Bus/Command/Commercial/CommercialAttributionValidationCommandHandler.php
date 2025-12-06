<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\ContactCommercialHistoryRepository;

class CommercialAttributionValidationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactCommercialHistoryRepository $contactCommercialHistoryRepository,
        private readonly AttributionStatusRepository        $attributionStatusRepository
    )
    {
    }

    public function __invoke(CommercialAttributionValidationCommand $command): CommercialAttributionValidationResponse
    {
        $contactCommercialHistory = $this->contactCommercialHistoryRepository->find(
            (int)$command->getId()
        );

        if (!$contactCommercialHistory instanceof ContactCommercialHistory) {
            throw new ResourceNotFoundException();
        }
        $status = $this->attributionStatusRepository->findOneByName('Accepted');
        $contactCommercialHistory->setStatus($status);
        $lastCommercialHistory = $this->contactCommercialHistoryRepository->findSecondToLastByCustomerId($contactCommercialHistory->getCustomer()->getId());
        if ($lastCommercialHistory instanceof ContactCommercialHistory) {
            $lastCommercialHistory->setToDate(new DateTime());
            $this->contactCommercialHistoryRepository->save($lastCommercialHistory, true);
        }

        $this->contactCommercialHistoryRepository->save($contactCommercialHistory, true);

        return new CommercialAttributionValidationResponse($contactCommercialHistory);
    }
}
