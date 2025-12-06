<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use DomainException;
use App\Common\Bus\Command\CommandHandler;
use App\Contact\Entity\ContactOrigin;
use App\Contact\Repository\ContactOriginRepository;

class UpdateContactOriginCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactOriginRepository $contactOriginRepository
    )
    {
    }

    public function __invoke(UpdateContactOriginCommand $command): ContactOrigin
    {
        $originId = $command->getOriginId();
        /** @var ContactOrigin|null $origin */
        $origin = $this->contactOriginRepository->find($originId);

        if (!$origin) {
            throw new DomainException("ContactOrigin with ID={$originId} not found.");
        }
        $origin->setLabel($command->getLabel());
        $this->contactOriginRepository->flush();

        return $origin;
    }
}
