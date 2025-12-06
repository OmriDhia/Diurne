<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Variation;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetDimension;
use App\Contremarque\Entity\CarpetDimensionValue;
use App\Contremarque\Entity\CarpetMaterial;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\DesignerAssignment;
use App\Contremarque\Entity\DesignerComposition;
use App\Contremarque\Entity\Image;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\ImageRepository;
use App\Contremarque\Service\CarpetReferenceGenerator;
use Doctrine\ORM\EntityManagerInterface;

class CreateImageVariationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ImageRepository $imageRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetStatusRepository $carpetStatusRepository,
        private readonly CarpetReferenceGenerator $referenceGenerator
    ) {}

    public function __invoke(CreateImageVariationCommand $command): ImageVariationResponse
    {
        $image = $this->imageRepository->findOneBy(['image_reference' => $command->variation_image_reference]);

        if (!$image instanceof Image) {
            throw new ResourceNotFoundException();
        }
        $carpetDesignOrder = $this->carpetDesignOrderRepository->find((int) $command->carpetDesignOrderId);
        if (!$carpetDesignOrder instanceof CarpetDesignOrder) {
            throw new ResourceNotFoundException();
        }
        $duplication = $this->carpetDesignOrderRepository->findOneBy(['variation' => $command->variation, 'variation_image_reference' => $command->variation_image_reference]);
        if ($duplication instanceof CarpetDesignOrder) {
            throw new ValidationException([sprintf('There is already variation with image %s variation %s', $command->variation_image_reference, $command->variation)]);
        }

        $newCarpetDesignOrder = new CarpetDesignOrder();
        $newCarpetDesignOrder->setLocation($carpetDesignOrder->getLocation());
        $oldSpec = $carpetDesignOrder->getCarpetSpecification();

        $carpetSpecification = $this->duplicateCarpetSpecification($oldSpec->getId());
        $newCarpetDesignOrder->setCarpetSpecification($carpetSpecification);
        $newCarpetDesignOrder->setProjectDi($carpetDesignOrder->getProjectDi());
        $newCarpetDesignOrder->setVariationImageReference($command->variation_image_reference);
        $newCarpetDesignOrder->setVariation($command->variation);
        $status = $this->carpetStatusRepository->getStatusByName('Attribué');
        $newCarpetDesignOrder->setStatus($status);
        $this->imageRepository->persist($newCarpetDesignOrder);
        $this->imageRepository->flush();
        $designers = $carpetDesignOrder->getDesigners();
        if ($designers->count()) {
            foreach ($designers as $designer) {
                $designerAssignment = new DesignerAssignment();
                $designerAssignment->setDesigner($designer->getDesigner());
                $designerAssignment->setCarpetDesignOrder($newCarpetDesignOrder);
                $designerAssignment->setDateFrom($designer->getDateFrom());
                $designerAssignment->setDateTo($designer->getDateTo());
                $designerAssignment->setInProgress($designer->isInProgress());
                $designerAssignment->setStopped($designer->isStopped());
                $designerAssignment->setDone($designer->isDone());
                $this->entityManager->persist($designerAssignment);
            }
        }
        $this->entityManager->flush();

        return new ImageVariationResponse(
            $image->getImageReference(),
            $newCarpetDesignOrder->getVariation(),
            $newCarpetDesignOrder->toArray()
        );
    }

    public function duplicateCarpetSpecification(int $carpetSpecificationId): CarpetSpecification
    {
        // Fetch the existing CarpetSpecification
        $existingSpec = $this->carpetSpecificationRepository->find($carpetSpecificationId);

        if (!$existingSpec instanceof CarpetSpecification) {
            throw new ResourceNotFoundException('CarpetSpecification not found.');
        }

        // Create a new CarpetSpecification and copy the fields
        $newSpec = new CarpetSpecification();
        $newSpec->setDescription($existingSpec->getDescription());
        $newSpec->setCollection($existingSpec->getCollection());
        $newSpec->setModel($existingSpec->getModel());
        $newSpec->setQuality($existingSpec->getQuality());
        $newSpec->setHasSpecialShape($existingSpec->hasSpecialShape());

        // Duplicate dimensions
        foreach ($existingSpec->getCarpetDimensions() as $existingDimension) {
            $newDimension = new CarpetDimension();
            $newDimension->setMesurement($existingDimension->getMesurement());
            $newDimension->setCarpetSpecification($newSpec);

            // Duplicate dimension values
            foreach ($existingDimension->getDimensionValues() as $existingValue) {
                $newValue = new CarpetDimensionValue();
                $newValue->setUnit($existingValue->getUnit());
                $newValue->setValue($existingValue->getValue());
                $newValue->addCarpetDimension($newDimension);
                $this->entityManager->persist($newValue);
                $newDimension->addDimensionValue($newValue);
                $this->entityManager->persist($newDimension);
            }

            $newSpec->addCarpetDimension($newDimension);
        }

        // Duplicate materials
        foreach ($existingSpec->getMaterials() as $existingMaterial) {
            $newMaterial = new CarpetMaterial();
            $newMaterial->setMaterial($existingMaterial->getMaterial());
            $newMaterial->setRate($existingMaterial->getRate());
            $newMaterial->setCarpetSpecification($newSpec);
            $this->entityManager->persist($newMaterial);
            $newSpec->addMaterial($newMaterial);
        }
        foreach ($existingSpec->getDesignerCompositions() as $existingDesignerComposition) {
            $newDesignerComposition = new DesignerComposition();
            $newDesignerComposition->setRate($existingDesignerComposition->getRate());
            $newDesignerComposition->setMaterial($existingDesignerComposition->getMaterial());
            $newDesignerComposition->setCarpetSpecification($newSpec);
            $this->entityManager->persist($newDesignerComposition);
            $newSpec->addDesignerComposition($newDesignerComposition);
        }
        $newSpec->setCarpetComposition($existingSpec->getCarpetComposition());
        // Associate with the existing CarpetDesignOrder or any other relationships
        $newSpec->setOversized($existingSpec->isOversized());
        $newSpec->setCarpetReference($this->referenceGenerator->getNextReference());
        // Persist and save the new CarpetSpecification
        $this->entityManager->persist($newSpec);
        $this->entityManager->flush();

        return $newSpec;
    }
}
