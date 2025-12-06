<?php

namespace App\Workshop\Bus\Command\DeleteWorkshopInformation;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Workshop\Repository\WorkshopInformationRepository;
use Doctrine\ORM\EntityNotFoundException;

class DeleteWorkshopInformationHandler implements CommandHandler
{
    public function __construct(
        private readonly WorkshopInformationRepository $repository
    )
    {
    }

    public function __invoke(DeleteWorkshopInformationCommand $command): WorkshopInformationResponse
    {
        $workshop = $this->repository->find($command->id);

        if (!$workshop) {
            throw new ResourceNotFoundException();
        }

        return new WorkshopInformationResponse($command->id);
    }
}