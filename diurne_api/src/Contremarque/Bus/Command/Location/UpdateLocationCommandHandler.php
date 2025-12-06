<?php

// src/Contremarque/Bus/Command/Location/UpdateLocationCommandHandler.php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Location;

use DateTime;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Contremarque\Entity\Location;
use App\Contremarque\Repository\CarpetTypeRepository;
use App\Contremarque\Repository\LocationRepository;

class UpdateLocationCommandHandler implements CommandHandler
{
    public function __construct(private readonly LocationRepository $locationRepository, private readonly CarpetTypeRepository $carpetTypeRepository)
    {
    }

    public function __invoke(UpdateLocationCommand $command): LocationResponse
    {
        $location = $this->locationRepository->find($command->id);

        if (!$location instanceof Location) {
            throw new ResourceNotFoundException('Location not found.');
        }

        // Update location properties if they are not null in the command
        if (null !== $command->carpetTypeId) {
            $carpetType = $this->carpetTypeRepository->find($command->carpetTypeId);
            $location->setCarpetType($carpetType);
        }
        if (null !== $command->description) {
            $location->setDescription($command->description);
        }
        if (null !== $command->quoteProcessed) {
            $location->setQuoteProcessed($command->quoteProcessed);
        }
        if (null !== $command->quoteProcessingDate) {
            $location->setQuoteProcessingDate($command->quoteProcessingDate ? new DateTime($command->quoteProcessingDate) : null);
        }
        if (null !== $command->priceMin) {
            $location->setPriceMin($command->priceMin);
        }
        if (null !== $command->priceMax) {
            $location->setPriceMax($command->priceMax);
        }
        if (null !== $command->updatedAt) {
            $location->setUpdatedAt($command->updatedAt ? new DateTime($command->updatedAt) : null);
        }
        // Add other properties as needed

        // Save changes
        $this->locationRepository->flush();

        return new LocationResponse($location);
    }
}
