<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use DomainException;
use App\Common\Bus\Command\CommandHandler;
use App\Contact\Repository\ContactOriginRepository;

class DeleteContactOriginCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactOriginRepository $contactOriginRepository
    )
    {
    }

    public function __invoke(DeleteContactOriginCommand $command): void
    {
        $originId = $command->getOriginId();
        $contactOrigin = $this->contactOriginRepository->find($originId);
        if (!$contactOrigin) {
            throw new DomainException("ContactOrigin with ID={$originId} not found.");
        }

        $this->contactOriginRepository->remove($contactOrigin);
        $this->contactOriginRepository->flush();
    }
}
