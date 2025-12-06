<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateQuoteCarpetSpecification;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\QuoteDetail;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\MesurementRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use App\Contremarque\Repository\UnitOfMeasurementRepository;
use App\Setting\Repository\CarpetCollectionRepository;
use App\Setting\Repository\CollectionGroupRepository;
use App\Setting\Repository\MaterialRepository;
use App\Setting\Repository\ModelRepository;
use App\Setting\Repository\QualityRepository;
use App\Setting\Repository\SpecialShapeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Contremarque\Service\CarpetReferenceGenerator;

class UpdateQuoteCarpetSpecificationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly CarpetCollectionRepository    $carpetCollectionRepository,
        private readonly QuoteDetailRepository         $quoteDetailRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly ModelRepository               $modelRepository,
        private readonly QualityRepository             $qualityRepository,
        private readonly SpecialShapeRepository        $specialShapeRepository,
        private readonly UnitOfMeasurementRepository   $unitOfMeasurementRepository,
        private readonly MesurementRepository          $mesurementRepository,
        private readonly CarpetDesignOrderRepository   $carpetDesignOrderRepository,
        private readonly MaterialRepository            $materialRepository,
        private readonly CarpetReferenceGenerator      $referenceGenerator,
        private readonly CollectionGroupRepository     $collectionGroupRepository
    ) {}

    public function __invoke(UpdateQuoteCarpetSpecificationCommand $command): UpdateQuoteCarpetSpecificationResponse
    {
        $dto = $command->carpetSpecificationDTO;

        $quoteDetailId = $command->quoteDetailId;
        // Fetch CarpetDesignOrder entity
        $quoteDetail = $this->quoteDetailRepository->find($quoteDetailId);
        if (!$quoteDetail instanceof QuoteDetail) {
            throw new ValidationException(['Quote detail not found.']);
        }

        if (!empty($command->getId())) {
            $id = $command->getId();
            // Fetch CarpetSpecification entity
            $carpetSpecification = $this->carpetSpecificationRepository->find($id);
            if (!$carpetSpecification instanceof CarpetSpecification) {
                throw new ValidationException(['Carpet specification not found.']);
            }
        } else {
            $carpetSpecification = $quoteDetail->getCarpetSpecification();
        }

        // Update fields if not empty
        if (!empty($dto->reference)) {
            $carpetSpecification->setCarpetReference($this->referenceGenerator->getNextReference());
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

        if (empty($specialShape = $carpetSpecification->getSpecialShape()) && !empty($dto->specialShapeId)) {
            $collectionGroup = $this->collectionGroupRepository->findOneBy(['groupNumber' => ($collection->getCollectionGroup()->getGroupNumber() + 1)]);
            $quoteDetail->setCollectionGroupUsedInCalcul($collectionGroup);
        } elseif (empty($dto->specialShapeId)) {
            $quoteDetail->setCollectionGroupUsedInCalcul($collection->getCollectionGroup());
        }
        if (!empty($dto->specialShapeId)) {
            $specialShape = $this->specialShapeRepository->find($dto->specialShapeId);
            $carpetSpecification->setSpecialShape($specialShape);
        } else {
            $carpetSpecification->setSpecialShape(null);
            $dto->hasSpecialShape = null;
        }
        if (null !== $dto->randomWeight) {
            $carpetSpecification->setGlobalWeight((string)$dto->randomWeight);
        }
        if (null !== $dto->hasSpecialShape) {
            $carpetSpecification->setHasSpecialShape($dto->hasSpecialShape ?? false);
        }
        if (null !== $dto->isOversized) {
            $carpetSpecification->setOversized($dto->isOversized);
        }
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
                    $this->entityManager->persist($carpetMaterial);
                }
            }
        }

        $this->entityManager->persist($carpetSpecification);

        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        return new UpdateQuoteCarpetSpecificationResponse($carpetSpecification, $quoteDetail);
    }

    private function findOrCreateDimension(CarpetSpecification $carpetSpecification, int $measurementId): CarpetDimension
    {
        foreach ($carpetSpecification->getCarpetDimensions() as $dimension) {
            if (empty($dimension->getMesurement())) {
                continue;
            }
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
        foreach ($carpetSpecification->getMaterials() as $material) {
            if ($material->getMaterial()->getId() === $materialId) {
                return $material;
            }
        }

        $carpetMaterial = new CarpetMaterial();
        $carpetMaterial->setCarpetSpecification($carpetSpecification);
        $carpetMaterial->setMaterial($this->materialRepository->find($materialId));

        return $carpetMaterial;
    }
}
