<?php

namespace App\Contremarque\Bus\Command\CarpetSpecification;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\DesignerComposition;
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
use App\Contremarque\Service\CarpetReferenceGenerator;

class CreateOrUpdateCarpetSpecificationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly CarpetCollectionRepository    $carpetCollectionRepository,
        private readonly ModelRepository               $modelRepository,
        private readonly QualityRepository             $qualityRepository,
        private readonly SpecialShapeRepository        $specialShapeRepository,
        private readonly UnitOfMeasurementRepository   $unitOfmesurementRepository,
        private readonly MesurementRepository          $mesurementRepository,
        private readonly CarpetDesignOrderRepository   $carpetDesignOrderRepository,
        private readonly MaterialRepository            $materialRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetReferenceGenerator      $referenceGenerator
    )
    {
    }

    public function __invoke(CreateOrUpdateCarpetSpecificationCommand $command): CarpetSpecification
    {
        $dto = $command->carpetSpecificationDTO;
        $carpetDesignOrderId = $command->carpetDesignOrderId;
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int)$carpetDesignOrderId);
        if (!$carpetDesignOrder instanceof CarpetDesignOrder) {
            throw new ValidationException(['Carpet design order not found.']);
        }

        // Fetch related entities
        $collection = $this->carpetCollectionRepository->find($dto->collectionId);
        $model = $this->modelRepository->find($dto->modelId);
        $quality = $this->qualityRepository->find($dto->qualityId);
        $specialShape = $this->specialShapeRepository->find((int)$dto->specialShapeId);

        // Create or update CarpetSpecification entity
        $carpetSpecification = new CarpetSpecification();
        $carpetSpecification
            ->setCarpetReference($this->referenceGenerator->getNextReference())
            ->setDescription($dto->description)
            ->setCollection($collection)
            ->setModel($model)
            ->setQuality($quality)
            ->setHasSpecialShape($dto->hasSpecialShape ?? false)
            ->setOversized($dto->isOversized)
            ->setSpecialShape($specialShape);

        // Validate dimensions exist before processing
        if (empty($dto->dimensions)) {
            throw new ValidationException(['Dimensions data is required.']);
        }

        // Add debug logging
        error_log('Processing dimensions: ' . print_r($dto->dimensions, true));

        // Handle dimensions
        foreach ($dto->dimensions as $measurement_id => $dimensionData) {
            $measurement = $this->mesurementRepository->find((int)$measurement_id);
            if (!$measurement) {
                throw new ValidationException(["Measurement with ID $measurement_id not found"]);
            }

            $dimension = new CarpetDimension();
            $dimension->setCarpetSpecification($carpetSpecification)
                ->setMesurement($measurement);

            if (empty($dimensionData)) {
                throw new ValidationException(["No dimension values provided for measurement $measurement_id"]);
            }

            foreach ($dimensionData as $item) {
                $unit = $this->unitOfmesurementRepository->find($item['dimension_id']);
                if (!$unit) {
                    throw new ValidationException(["Unit with ID {$item['dimension_id']} not found"]);
                }

                $carpetDimensionValue = new CarpetDimensionValue();
                $carpetDimensionValue->setUnit($unit)
                    ->setValue((float)$item['value']);

                $this->entityManager->persist($carpetDimensionValue);
                $dimension->addDimensionValue($carpetDimensionValue);
            }

            $this->entityManager->persist($dimension);
            $carpetSpecification->addCarpetDimension($dimension);
        }

        // Handle materials
        foreach ($dto->materials as $materialData) {
            $material = $this->materialRepository->find($materialData['material_id']);
            if (!$material) {
                continue;
            }

            $carpetMaterial = new CarpetMaterial();
            $carpetMaterial->setMaterial($material)
                ->setRate((float)$materialData['rate'])
                ->setCarpetSpecification($carpetSpecification);

            $this->entityManager->persist($carpetMaterial);
            $carpetSpecification->addMaterial($carpetMaterial);

            $designerComposition = new DesignerComposition();
            $designerComposition->setMaterial($material)
                ->setRate((float)$materialData['rate'])
                ->setCarpetSpecification($carpetSpecification);

            $this->entityManager->persist($designerComposition);
            $carpetSpecification->addDesignerComposition($designerComposition);
        }

        $carpetSpecification->setCarpetDesignOrder($carpetDesignOrder);
        $this->entityManager->persist($carpetSpecification);
        $this->entityManager->flush();

        return $carpetSpecification;
    }
}
