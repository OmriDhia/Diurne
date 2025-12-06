<?php

namespace App\Contremarque\Bus\Command\CreateQuoteCarpetSpecification;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\QuoteDetail;
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

class CreateQuoteCarpetSpecificationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly CarpetCollectionRepository  $carpetCollectionRepository,
        private readonly ModelRepository             $modelRepository,
        private readonly QualityRepository           $qualityRepository,
        private readonly SpecialShapeRepository      $specialShapeRepository,
        private readonly UnitOfMeasurementRepository $unitOfmesurementRepository,
        private readonly MesurementRepository        $mesurementRepository,
        private readonly QuoteDetailRepository       $quoteDetailRepository,
        private readonly MaterialRepository          $materialRepository,
        private readonly CollectionGroupRepository   $collectionGroupRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetReferenceGenerator $referenceGenerator
    ) {}

    public function __invoke(CreateQuoteCarpetSpecificationCommand $command): QuoteCarpetSpecificationResponse
    {
        $dto = $command->carpetSpecificationDTO;
        $quoteDetailId = $command->quoteDetailId;
        $quoteDetail = $this->quoteDetailRepository->find((int)$quoteDetailId);
        if (!$quoteDetail instanceof QuoteDetail) {
            throw new ValidationException(['Quote detail not found.']);
        }
        // Fetch related entities
        $collection = $this->carpetCollectionRepository->find($dto->collectionId);
        $model = $this->modelRepository->find($dto->modelId);
        $quality = $this->qualityRepository->find($dto->qualityId);

        $specialShape = null;

        if (!empty($dto->specialShapeId)) {
            $specialShape = $this->specialShapeRepository->find($dto->specialShapeId);
        }


        $carpetSpecification = new CarpetSpecification();
        $carpetSpecification
            ->setCarpetReference($this->referenceGenerator->getNextReference())
            ->setDescription($dto->description)
            ->setCollection($collection)
            ->setModel($model)
            ->setQuality($quality)
            ->setHasSpecialShape($dto->hasSpecialShape ?? false)
            ->setOversized($dto->isOversized ?? false)
            ->setSpecialShape($dto->specialShapeId !== null ? $specialShape : null);


        // Handle dimensions
        // if (!empty($dto->dimensions)) {
        //     foreach ($dto->dimensions as $measurementId => $dimensionData) {
        //         $dimension = $this->findOrCreateDimension($carpetSpecification, $measurementId);

        //         if (!empty($dimensionData)) {
        //             foreach ($dimensionData as $item) {
        //                 $unit = $this->unitOfmesurementRepository->find($item['dimension_id']);
        //                 $carpetDimensionValue = new CarpetDimensionValue();
        //                 $carpetDimensionValue->setUnit($unit);

        //                 $carpetDimensionValue->setValue((string) $item['value']);
        //                 $this->entityManager->persist($carpetDimensionValue);
        //                 $dimension->addDimensionValue($carpetDimensionValue);
        //             }
        //             $this->entityManager->persist($dimension);
        //         }

        //         // Add dimension to CarpetSpecification
        //         $carpetSpecification->addCarpetDimension($dimension);
        //     }
        // }

        if (!empty($dto->materials)) {
            foreach ($dto->materials as $materialData) {
                $material = $this->materialRepository->find($materialData['material_id']);
                if ($material) {
                    $carpetMaterial = new CarpetMaterial();
                    $carpetMaterial->setMaterial($material);
                    $carpetMaterial->setRate($materialData['rate']);
                    $carpetMaterial->setCarpetSpecification($carpetSpecification);

                    $carpetSpecification->addMaterial($carpetMaterial);
                    $this->entityManager->persist($carpetMaterial);
                }
            }
        }

        $this->entityManager->persist($carpetSpecification);
        $quoteDetail->setCarpetSpecification($carpetSpecification);
        if (!empty($specialShape) && !empty($specialShape->getId())) {
            $collectionGroup = $this->collectionGroupRepository->findOneBy(['groupNumber' => ($collection->getCollectionGroup()->getGroupNumber() + 1)]);
            $quoteDetail->setCollectionGroupUsedInCalcul($collectionGroup);
        } else {
            $quoteDetail->setCollectionGroupUsedInCalcul($collection->getCollectionGroup());
        }
        $this->entityManager->persist($quoteDetail);
        $this->entityManager->flush();

        return new QuoteCarpetSpecificationResponse($carpetSpecification, $quoteDetail);
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
}
