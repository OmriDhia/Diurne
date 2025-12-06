<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contact\Entity\ContactCommercialHistory;
use App\Contact\Repository\AttributionStatusRepository;
use App\Contact\Repository\ContactCommercialHistoryRepository;

class CommercialAttributionAnnulationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactCommercialHistoryRepository $contactCommercialHistoryRepository,
        private readonly AttributionStatusRepository $attributionStatusRepository
    ) {
    }

    public function __invoke(CommercialAttributionAnnulationCommand $command): CommercialAttributionValidationResponse
    {
        $contactCommercialHistory = $this->contactCommercialHistoryRepository->find(
            (int) $command->getId()
        );
        if (!$contactCommercialHistory instanceof ContactCommercialHistory) {
            throw new ResourceNotFoundException();
        }
        $status = $this->attributionStatusRepository->findOneByName('Refused');
        $contactCommercialHistory->setStatus($status);
        $this->contactCommercialHistoryRepository->save($contactCommercialHistory, true);

        return new CommercialAttributionValidationResponse($contactCommercialHistory);
    }
}
