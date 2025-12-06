<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetSpecification;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\SpecialShapeRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateCarpetSpecificationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetCollectionRepository $carpetCollectionRepository,
        private readonly ModelRepository $modelRepository,
        private readonly QualityRepository $qualityRepository,
        private readonly SpecialShapeRepository $specialShapeRepository,
        private readonly UnitOfMeasurementRepository $unitOfMeasurementRepository,
        private readonly MesurementRepository $mesurementRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly MaterialRepository $materialRepository
    ) {}

    public function __invoke(UpdateCarpetSpecificationCommand $command): CarpetSpecification
    {
        $dto = $command->carpetSpecificationDTO;
        $carpetDesignOrderId = $command->carpetDesignOrderId;
        $id = $command->id;

        // Fetch CarpetDesignOrder entity
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find($carpetDesignOrderId);
        if (!$carpetDesignOrder instanceof CarpetDesignOrder) {
            throw new ValidationException(['Carpet design order not found.']);
        }

        // Fetch CarpetSpecification entity
        $carpetSpecification = $this->carpetSpecificationRepository->find($id);
        if (!$carpetSpecification instanceof CarpetSpecification) {
            throw new ValidationException(['Carpet specification not found.']);
        }

        if (!empty($dto->description)) {
            $carpetSpecification->setDescription($dto->description);
        }
        if (!empty($dto->collectionId)) {
            $collection = $this->carpetCollectionRepository->find($dto->collectionId);
            $carpetSpecification->setCollection($collection);
        }
        if (!empty($dto->modelId)) {
            $model = $this->modelRepository->find($dto->modelId);
            $carpetSpecification->setModel($model);
        }
        if (!empty($dto->qualityId)) {
            $quality = $this->qualityRepository->find($dto->qualityId);
            $carpetSpecification->setQuality($quality);
        }
        if (!empty($dto->specialShapeId)) {
            $specialShape = $this->specialShapeRepository->find($dto->specialShapeId);
            $carpetSpecification->setSpecialShape($specialShape);
        }
        $carpetSpecification->setHasSpecialShape($dto->hasSpecialShape ?? false);
        $carpetSpecification->setOversized($dto->isOversized);

        if ($carpetSpecification->getCarpetDimensions()->count()) {
            foreach ($carpetSpecification->getCarpetDimensions() as $index => $dimensionObj) {
                $this->entityManager->remove($dimensionObj);
                $this->entityManager->flush();
            }
        }

        // Handle dimensions
        if (!empty($dto->dimensions)) {
            foreach ($dto->dimensions as $measurementId => $dimensionData) {
                $dimension = $this->findOrCreateDimension($carpetSpecification, $measurementId);

                if (!empty($dimensionData)) {
                    foreach ($dimensionData as $item) {
                        $unit = $this->unitOfMeasurementRepository->find($item['dimension_id']);
                        $carpetDimensionValue = new CarpetDimensionValue();
                        $carpetDimensionValue->setUnit($unit);

                        $carpetDimensionValue->setValue((string) $item['value']);
                        $this->entityManager->persist($carpetDimensionValue);
                        $dimension->addDimensionValue($carpetDimensionValue);
                    }
                    $this->entityManager->persist($dimension);
                }

                // Add dimension to CarpetSpecification
                $carpetSpecification->addCarpetDimension($dimension);
            }
        }

        if ($carpetSpecification->getMaterials()->count()) {
            foreach ($carpetSpecification->getMaterials() as $index => $material) {
                $this->entityManager->remove($material);
                $this->entityManager->flush();
            }
        }

        // Handle materials
        if (!empty($dto->materials)) {
            foreach ($dto->materials as $materialData) {
                $material = $this->materialRepository->find($materialData['material_id']);
                if ($material) {
                    $materialObject = $this->materialRepository->find((int)$materialData['material_id']);
                    if (!$materialObject) {
                        throw new ValidationException(['Material not found.']);
                    }

                    $carpetMaterial = new CarpetMaterial();
                    $carpetMaterial->setMaterial($materialObject);
                    $carpetMaterial->setRate((string) $materialData['rate']);
                    $this->entityManager->persist($carpetMaterial);
                    $carpetSpecification->addMaterial($carpetMaterial);
                }
            }
        }

        $carpetSpecification->setCarpetDesignOrder($carpetDesignOrder);
        $this->entityManager->persist($carpetSpecification);
        $this->entityManager->flush();
        return $carpetSpecification;
    }

    private function findOrCreateDimension(CarpetSpecification $carpetSpecification, int $measurementId): CarpetDimension
    {
        foreach ($carpetSpecification->getCarpetDimensions() as $dimension) {
            if ($dimension->getMesurement()->getId() === $measurementId) {
                return $dimension;
            }
        }

        $dimension = new CarpetDimension();
        $dimension->setCarpetSpecification($carpetSpecification);
        $dimension->setMesurement($this->mesurementRepository->find($measurementId));

        return $dimension;
    }

    private function findOrCreateMaterial(CarpetSpecification $carpetSpecification, int $materialId): CarpetMaterial
    {

        $materialObject = $this->materialRepository->find((int)$materialId);
        if (!$materialObject) {
            throw new ValidationException(['Material not found.']);
        }

        $carpetMaterial = new CarpetMaterial();
        $carpetMaterial->setCarpetSpecification($carpetSpecification);
        $carpetMaterial->setMaterial($materialObject);

        return $carpetMaterial;
    }
}
