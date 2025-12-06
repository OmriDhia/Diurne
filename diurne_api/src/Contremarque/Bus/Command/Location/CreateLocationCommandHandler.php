<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Location;

use DateTimeImmutable;
use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetReference;
use App\Contremarque\Entity\Contremarque;
use App\Contremarque\Entity\Location;
use App\Contremarque\Repository\CarpetReferenceRepository;
use App\Contremarque\Repository\CarpetTypeRepository;
use App\Contremarque\Repository\ContremarqueRepository;
use App\Contremarque\Repository\LocationRepository;

class CreateLocationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ContremarqueRepository $contremarqueRepository,
        private readonly CarpetTypeRepository $carpetTypeRepository,
        private readonly LocationRepository $locationRepository,
        private readonly CarpetReferenceRepository $carpetReferenceRepository
    ) {}

    public function __invoke(CreateLocationCommand $command): LocationResponse
    {
        $contremarque = $this->contremarqueRepository->find((int) $command->getContremarqueId());

        if (!$contremarque instanceof Contremarque) {
            throw new ValidationException(['There is no contremarque with this id.']);
        }

        $carpetType = $this->carpetTypeRepository->find((int) $command->getCarpetTypeId());
        if (!$carpetType) {
            throw new ValidationException(['There is no carpet type with this id.']);
        }

        // Check if a Location with the same carpetType and description already exists
        $existingLocation = $this->locationRepository->findOneBy([
            'carpetType' => $carpetType,
            'description' => $command->getDescription(),
            'contremarque' => $contremarque
        ]);

        if ($existingLocation instanceof Location) {
            throw new ValidationException([
                "A location with carpet type ID {$command->getCarpetTypeId()} and description '{$command->getDescription()}' already exists."
            ]);
        }

        // Proceed with creating the new Location
        $location = new Location();
        $location->setContremarque($contremarque);
        $location->setCarpetType($carpetType);
        $location->setDescription($command->getDescription());
        $location->setQuoteProcessed($command->getQuoteProcessed());
        if (null !== $command->getQuoteProcessingDate()) {
            $quoteProcessingDate = new DateTimeImmutable($command->getQuoteProcessingDate());
            $location->setQuoteProcessingDate($quoteProcessingDate);
        }
        if ($command->getPriceMax() !== null) {
            $location->setPriceMax((string) $command->getPriceMax());
        }
        if ($command->getPriceMin() !== null) {
            $location->setPriceMin((string) $command->getPriceMin());
        }

        $location->setUpdatedAt(new DateTimeImmutable());
        $location->setCreatedAt(new DateTimeImmutable());
        $this->locationRepository->persist($location);
        $this->locationRepository->flush();

        $carpetReference = new CarpetReference();
        $carpetReference->setLocation($location);
        $carpetReference->setContremarque($contremarque);
        $carpetReference->setReference($this->carpetReferenceRepository->getLastReference($contremarque));
        $carpetReference->setSequenceNumber((int) $this->carpetReferenceRepository->getLastSequenceNumber($contremarque));
        $this->carpetReferenceRepository->persist($carpetReference);
        $this->carpetReferenceRepository->flush();

        return new LocationResponse(
            $location
        );
    }
}
