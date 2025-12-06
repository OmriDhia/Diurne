<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\ManufacturerRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\TarifGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Common\Exception\ResourceNotFoundException;

class CreateManufacturerPriceGridCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly ManufacturerPriceGridRepository $priceGridRepository,
        private readonly ManufacturerRepository          $manufacturerRepository,
        private readonly QualityRepository               $qualityRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly MaterialRepository              $materialRepository,
        private readonly TarifGroupRepository            $tarifGroupRepository
    )
    {
    }

    public function __invoke(CreateManufacturerPriceGridCommand $command): ManufacturerPriceGridResponse
    {
        // Vérifier l'existence d'une grille pour le même fabricant, qualité et tarif group
        $existingGrid = $this->priceGridRepository->findOneBy([
            'manufacturer' => $command->getManufacturerId(),
            'quality' => $command->getQualityId(),
            'tarifGroup' => $command->getTarifGroupId()
        ]);

        if ($existingGrid) {
            throw new DuplicateValidationResourceException(
                'A price grid already exists for this manufacturer, quality and tarif group.'
            );
        }

        // Vérifier l'existence du fabricant et de la qualité
        $manufacturer = $this->manufacturerRepository->find($command->getManufacturerId());
        if (!$manufacturer) {
            throw new ResourceNotFoundException($command->getManufacturerId());
        }

        $quality = $this->qualityRepository->find($command->getQualityId());
        if (!$quality) {
            throw new \InvalidArgumentException('Quality not found.');
        }

        $tarifGroup = $this->tarifGroupRepository->find($command->getTarifGroupId());

        if (!$tarifGroup) {
            throw new ResourceNotFoundException($command->getTarifGroupId());
        }

        // Créer la nouvelle grille tarifaire
        $priceGrid = new ManufacturerPriceGrid();
        $priceGrid->setManufacturer($manufacturer);
        $priceGrid->setQuality($quality);
        $priceGrid->setTarifGroup($tarifGroup);
        $priceGrid->setTariffGrid($command->getTariffGrid());
        $priceGrid->setKnots($command->getKnots());
        $priceGrid->setSpecial($command->getSpecial());
        $priceGrid->setStandardVelours($command->getStandardVelours());
        $priceGrid->setIsActive($command->isActive());

        // If the command provided prices, create those first and remember processed materials
        $processedMaterialIds = [];
        $providedPrices = $command->getPrices() ?? [];

        if (is_array($providedPrices) && count($providedPrices) > 0) {
            foreach ($providedPrices as $p) {
                if (!is_array($p)) {
                    continue;
                }

                $materialId = $p['materialId'] ?? $p['id'] ?? null;
                if (!$materialId) {
                    continue;
                }

                $material = $this->materialRepository->find((int)$materialId);
                if (!$material) {
                    // skip invalid material ids
                    continue;
                }

                $priceValue = isset($p['price']) ? (string)$p['price'] : '0.00';

                try {
                    $effectiveDate = isset($p['effectiveDate']) && $p['effectiveDate'] !== null
                        ? new \DateTimeImmutable($p['effectiveDate'])
                        : $this->getEffectiveDateFromTarifGroup($priceGrid);
                } catch (\Throwable $e) {
                    $effectiveDate = $this->getEffectiveDateFromTarifGroup($priceGrid);
                }

                $manufacturerPrice = $this->manufacturerPriceRepository->create([
                    'priceGrid' => $priceGrid,
                    'material' => $material,
                    'price' => $priceValue,
                    'effectiveDate' => $effectiveDate,
                ]);

                $priceGrid->addManufacturerPrice($manufacturerPrice);
                $processedMaterialIds[(int)$material->getId()] = true;
            }
        }

        // Fill remaining materials with default zero price
        foreach ($this->materialRepository->findAll() as $material) {
            if (isset($processedMaterialIds[(int)$material->getId()])) {
                continue;
            }

            $manufacturerPrice = $this->manufacturerPriceRepository->create([
                'priceGrid' => $priceGrid,
                'material' => $material,
                'price' => '0.00',
                'effectiveDate' => $this->getEffectiveDateFromTarifGroup($priceGrid),
            ]);

            $priceGrid->addManufacturerPrice($manufacturerPrice);
        }

        $this->entityManager->persist($priceGrid);
        $this->entityManager->flush();

        return new ManufacturerPriceGridResponse($priceGrid);
    }

    private function getEffectiveDateFromTarifGroup(ManufacturerPriceGrid $priceGrid): \DateTimeImmutable
    {
        $year = (int)$priceGrid->getTarifGroup()?->getYear();

        if ($year <= 0) {
            throw new \InvalidArgumentException('Tarif group year must be a valid year.');
        }

        return new \DateTimeImmutable(sprintf('%d-01-01', $year));
    }
}
