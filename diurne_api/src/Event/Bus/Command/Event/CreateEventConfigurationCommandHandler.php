<?php

declare(strict_types=1);

namespace App\Event\Bus\Command\Event;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Event\Entity\EventNomenclature;
use App\Event\Repository\EventNomenclatureRepository;

class CreateEventConfigurationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EventNomenclatureRepository $eventNomenclatureRepository
    ) {
    }

    public function __invoke(CreateEventConfigurationCommand $command): EventConfigurationResponse
    {
        $eventNomenclature = $this->eventNomenclatureRepository->findBySubject($command->getSubject());

        if ($eventNomenclature instanceof EventNomenclature) {
            throw new DuplicateValidationResourceException();
        }

        $eventNomenclature = $this->eventNomenclatureRepository->create(
            [
                'subject' => $command->getSubject(),
                'is_automatic' => $command->isIsAutomatic(),
                'automatic_followup_delay' => $command->getAutomaticFollowupDelay(),
            ]
        );

        return new EventConfigurationResponse($eventNomenclature);
    }
}
