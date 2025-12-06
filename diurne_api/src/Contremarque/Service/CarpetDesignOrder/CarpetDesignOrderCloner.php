<?php

declare(strict_types=1);

namespace App\Contremarque\Service\CarpetDesignOrder;

use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetSpecification;
use App\Contremarque\Entity\Image;
use App\Contremarque\Entity\ImageAttachment;
use App\Contremarque\Entity\Attachment;
use App\Contremarque\Repository\CarpetSpecificationRepository;
use App\Contremarque\Service\CarpetReferenceGenerator;

class CarpetDesignOrderCloner
{
    public function __construct(
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository,
        private readonly CarpetReferenceGenerator      $referenceGenerator
    )
    {
    }

    public function clone(CarpetDesignOrder $original): CarpetDesignOrder
    {
        $newOrder = clone $original;
        $newOrder->resetUniqueFields();

        foreach ($original->getDesigners() as $designer) {
            $newOrder->addDesigner(clone $designer);
        }

        foreach ($original->getCarpetDesignOrderAttachments() as $attachment) {
            $newOrder->addCarpetDesignOrderAttachment(clone $attachment);
        }

        foreach ($original->getImages() as $image) {
            $clonedImage = clone $image;
            $clonedImage->setCarpetDesignOrder($newOrder);

            $imageAttachment = $image->getImageAttachment();
            if ($imageAttachment instanceof ImageAttachment) {
                $clonedAttachment = clone $imageAttachment;
                $attachment = $imageAttachment->getAttachment();
                if ($attachment instanceof Attachment) {
                    $clonedAttachment->setAttachment(clone $attachment);
                }
                $clonedImage->setImageAttachment($clonedAttachment);
            }

            $newOrder->addImage($clonedImage);
        }

        $spec = $original->getCarpetSpecification();
        if ($spec) {
            $newSpec = clone $spec;
            $newSpec->resetUniqueFields();
            $newSpec->setCarpetReference($this->referenceGenerator->getNextReference());
            $this->cloneSpecificationRelated($spec, $newSpec);
            $newOrder->setCarpetSpecification($newSpec);
        }

        return $newOrder;
    }

    private function cloneSpecificationRelated(CarpetSpecification $original, CarpetSpecification $clone): void
    {
        foreach ($original->getCarpetDimensions() as $dimension) {
            $clone->addCarpetDimension(clone $dimension);
        }
        foreach ($original->getMaterials() as $material) {
            $clone->addMaterial(clone $material);
        }
        foreach ($original->getDesignerCompositions() as $composition) {
            $clone->addDesignerComposition(clone $composition);
        }

        $origComposition = $original->getCarpetComposition();
        if ($origComposition) {
            $newComposition = clone $origComposition;
            $newComposition->resetUniqueFields();
            foreach ($origComposition->getThreads() as $thread) {
                $clonedThread = clone $thread;
                $clonedThread->setCarpetComposition($newComposition);
                $newComposition->addThread($clonedThread);
            }
            foreach ($origComposition->getLayers() as $layer) {
                $clonedLayer = clone $layer;
                foreach ($layer->getLayerDetails() as $layerDetail) {
                    $clonedLayerDetail = clone $layerDetail;
                    $clonedLayer->addLayerDetail($clonedLayerDetail);
                }
                $clonedLayer->setCarpetComposition($newComposition);
                $newComposition->addLayer($clonedLayer);
            }
            $clone->setCarpetComposition($newComposition);
        }
    }
}
