<?php

namespace App\Setting\Bus\Command\ManufacturerPriceGrid;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Repository\ManufacturerPriceRepository;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\TarifGroupRepository;
use App\Setting\Repository\ManufacturerRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateManufacturerPriceGridCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface          $entityManager,
        private readonly ManufacturerPriceGridRepository $priceGridRepository,
        private readonly ManufacturerPriceRepository     $manufacturerPriceRepository,
        private readonly MaterialRepository              $materialRepository,
        private readonly TarifGroupRepository            $tarifGroupRepository,
        private readonly ManufacturerRepository          $manufacturerRepository
    )
    {
    }

    public function __invoke(UpdateManufacturerPriceGridCommand $command): ManufacturerPriceGridResponse
    {
        $priceGrid = $this->priceGridRepository->find($command->getId());

        if (!$priceGrid) {
            throw new ResourceNotFoundException('Price grid not found.');
        }

        // Update manufacturer if provided
        if (null !== $command->getManufacturerId()) {
            $manufacturer = $this->manufacturerRepository->find($command->getManufacturerId());
            if (!$manufacturer) {
                throw new ResourceNotFoundException('Manufacturer not found.');
            }

            $priceGrid->setManufacturer($manufacturer);
        }

        if (null !== $command->getTarifGroupId()) {
            $tarifGroup = $this->tarifGroupRepository->find($command->getTarifGroupId());

            if (!$tarifGroup) {
                throw new ResourceNotFoundException($command->getTarifGroupId());
            }

            $priceGrid->setTarifGroup($tarifGroup);
            $this->updateEffectiveDates($priceGrid);
        }

        if (null !== $command->getTariffGrid()) {
            $priceGrid->setTariffGrid($command->getTariffGrid());
        }

        if (null !== $command->getKnots()) {
            $priceGrid->setKnots($command->getKnots());
        }

        if (null !== $command->getSpecial()) {
            $priceGrid->setSpecial($command->getSpecial());
        }

        if (null !== $command->getStandardVelours()) {
            $priceGrid->setStandardVelours($command->getStandardVelours());
        }

        if (null !== $command->isActive()) {
            $priceGrid->setIsActive($command->isActive());
        }

        // Process provided prices array (update existing or create new ones)
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
                    continue;
                }

                // Check in-memory collection first to avoid duplicates
                $existingPrice = null;
                foreach ($priceGrid->getManufacturerPrices() as $mp) {
                    if ($mp->getMaterial()?->getId() === $material->getId()) {
                        $existingPrice = $mp;
                        break;
                    }
                }

                if (!$existingPrice) {
                    $existingPrice = $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material)
                        ?? $this->manufacturerPriceRepository->create([
                            'priceGrid' => $priceGrid,
                            'material' => $material,
                            'price' => '0.00',
                            'effectiveDate' => $this->getEffectiveDateFromTarifGroup($priceGrid),
                        ]);

                    $priceGrid->addManufacturerPrice($existingPrice);
                }

                // Set price and effective date from payload (or fallback to tarifGroup year)
                $existingPrice->setPrice(isset($p['price']) ? (string)$p['price'] : $existingPrice->getPrice());

                try {
                    $effectiveDate = isset($p['effectiveDate']) && $p['effectiveDate'] !== null
                        ? new \DateTimeImmutable($p['effectiveDate'])
                        : $this->getEffectiveDateFromTarifGroup($priceGrid);
                } catch (\Throwable $e) {
                    $effectiveDate = $this->getEffectiveDateFromTarifGroup($priceGrid);
                }

                $existingPrice->setEffectiveDate($effectiveDate);
            }
        }

        $this->ensureManufacturerPricesForMaterials($priceGrid);

        $this->entityManager->flush();

        return new ManufacturerPriceGridResponse($priceGrid);
    }

    private function ensureManufacturerPricesForMaterials(ManufacturerPriceGrid $priceGrid): void
    {
        $effectiveDate = $this->getEffectiveDateFromTarifGroup($priceGrid);

        foreach ($this->materialRepository->findAll() as $material) {
            // prefer in-memory collection first to avoid duplicates
            $existingPrice = null;
            foreach ($priceGrid->getManufacturerPrices() as $mp) {
                if ($mp->getMaterial()?->getId() === $material->getId()) {
                    $existingPrice = $mp;
                    break;
                }
            }

            $manufacturerPrice = $existingPrice
                ?? $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material)
                ?? $this->manufacturerPriceRepository->create([
                    'priceGrid' => $priceGrid,
                    'material' => $material,
                    'price' => '0.00',
                    'effectiveDate' => $effectiveDate,
                ]);

            $manufacturerPrice->setEffectiveDate($effectiveDate);
            $priceGrid->addManufacturerPrice($manufacturerPrice);
        }
    }

    private function updateEffectiveDates(ManufacturerPriceGrid $priceGrid): void
    {
        $effectiveDate = $this->getEffectiveDateFromTarifGroup($priceGrid);

        foreach ($priceGrid->getManufacturerPrices() as $price) {
            $price->setEffectiveDate($effectiveDate);
        }
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
