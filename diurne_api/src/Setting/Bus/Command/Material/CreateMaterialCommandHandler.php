<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\Material;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Setting\Entity\Material;
use App\Setting\Entity\MaterialLang;
use App\Setting\Repository\LanguageRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ManufacturerPriceGridRepository;
use App\Setting\Repository\ManufacturerPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateMaterialCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LanguageRepository $languageRepository,
        private readonly ValidatorInterface $validator, // Add ValidatorInterface
        private readonly ManufacturerPriceGridRepository $manufacturerPriceGridRepository,
        private readonly ManufacturerPriceRepository $manufacturerPriceRepository
    ) {
    }

    public function __invoke(CreateMaterialCommand $command): Material
    {
        // Check if a material with the same reference already exists
        $existingMaterial = $this->materialRepository->findOneBy(['reference' => $command->reference]);
        if ($existingMaterial) {
            throw new InvalidArgumentException('A material with this reference already exists.');
        }

        $material = new Material();
        $material->setReference($command->reference);

        foreach ($command->descriptions as $descriptionData) {
            $materialLang = new MaterialLang();
            $materialLang->setMaterial($material);
            $language = $this->languageRepository->find($descriptionData['language_id']);
            if (!$language) {
                throw new InvalidArgumentException('Invalid language ID: '.$descriptionData['language_id']);
            }
            $materialLang->setLanguage($language);
            $materialLang->setLabel($descriptionData['label']);
            $this->entityManager->persist($materialLang);
        }

        $this->entityManager->persist($material);
        $this->entityManager->flush();

        $this->createManufacturerPricesForExistingGrids($material);

        return $material;
    }

    private function createManufacturerPricesForExistingGrids(Material $material): void
    {
        foreach ($this->manufacturerPriceGridRepository->findAll() as $priceGrid) {
            $existingPrice = $this->manufacturerPriceRepository->findOneByGridAndMaterial($priceGrid, $material);

            if ($existingPrice) {
                continue;
            }

            $tarifGroup = $priceGrid->getTarifGroup();
            $year = (int) $tarifGroup?->getYear();

            if ($year <= 0) {
                continue;
            }

            $effectiveDate = new \DateTimeImmutable(sprintf('%d-01-01', $year));

            $manufacturerPrice = $this->manufacturerPriceRepository->create([
                'priceGrid' => $priceGrid,
                'material' => $material,
                'price' => '0.00',
                'effectiveDate' => $effectiveDate,
            ]);

            $priceGrid->addManufacturerPrice($manufacturerPrice);
        }
    }
}
