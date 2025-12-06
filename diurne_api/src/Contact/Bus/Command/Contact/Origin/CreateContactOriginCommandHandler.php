<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Contact\Origin;

use App\Common\Bus\Command\CommandHandler;
use App\Contact\Entity\ContactOrigin;
use App\Contact\Repository\ContactOriginRepository;

class CreateContactOriginCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContactOriginRepository $contactOriginRepository
    )
    {
    }

    public function __invoke(CreateContactOriginCommand $command): ContactOrigin
    {
        $origin = new ContactOrigin();
        $origin->setLabel($command->getLabel());
        $this->contactOriginRepository->persist($origin);
        $this->contactOriginRepository->flush();

        return $origin;
    }
}
