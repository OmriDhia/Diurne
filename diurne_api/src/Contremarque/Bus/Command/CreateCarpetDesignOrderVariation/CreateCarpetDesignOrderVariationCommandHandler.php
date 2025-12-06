<?php

namespace App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation;

use Exception;
use App\Common\Bus\Command\CommandHandler;
use App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation\CarpetDesignOrderVariationResponse;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\CarpetSpecification; // Ensure this is imported
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Bus\Command\CreateCarpetDesignOrderVariation\CreateCarpetDesignOrderVariationCommand;
use App\Contremarque\Repository\CarpetStatusRepository;
use App\Contremarque\Repository\CarpetSpecificationRepository; // Ensure repository is imported

class CreateCarpetDesignOrderVariationCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CarpetStatusRepository $carpetStatusRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly CarpetSpecificationRepository $carpetSpecificationRepository // Inject CarpetSpecificationRepository
    ) {}

    public function __invoke(CreateCarpetDesignOrderVariationCommand $command): CarpetDesignOrderVariationResponse
    {
        // Récupérer la commande originale
        $originalOrder = $this->carpetDesignOrderRepository->find($command->getOrderId());
        if (!$originalOrder) {
            throw new Exception('Carpet Design Order not found.');
        }

        // Cloner la commande originale pour créer une nouvelle variation
        $newOrder = clone $originalOrder;
        $newOrder->resetUniqueFields();
        foreach ($originalOrder->getDesigners() as $designer) {
            $newOrder->addDesigner(clone $designer);
        }
        foreach ($originalOrder->getCarpetDesignOrderAttachments() as $carpetDesignOrderAttachment) {
            $newOrder->addCarpetDesignOrderAttachment(clone $carpetDesignOrderAttachment);
        }

        // Cloner la CarpetSpecification si elle existe
        $carpetSpecification = $originalOrder->getCarpetSpecification();
        if ($carpetSpecification) {
            //clone related Entities
            $newSpecification = clone $carpetSpecification;
            $newSpecification->resetUniqueFields();
            // Clone the related entities of CarpetSpecification
            $this->cloneRelatedEntities($carpetSpecification, $newSpecification);
            $newOrder->setCarpetSpecification($newSpecification); // Associer la specification clonée à la nouvelle commande
        }

        // Récupérer le champ parent de la variation
        $variatedFromOrderId = $originalOrder->getId();

        // Récupérer toutes les variations existantes liées à ce parent (groupées par parent)
        $existingOrders = $this->carpetDesignOrderRepository->findBy([
            'variatedFromOrderId' => $variatedFromOrderId
        ]);

        // Extraire les numéros de variation existants
        $existingVariations = [];
        foreach ($existingOrders as $order) {
            $existingVariations[] = (int) ($order->getVariation() ?? '01');
        }

        // Calculer la nouvelle variation
        $newVariation = $command->getVariation() ?? '01'; // Si pas spécifiée, on commence avec '01'

        // Si des variations existent, incrémenter la dernière variation
        if (empty($command->getVariation()) && !empty($existingVariations)) {
            // Comptez le nombre d'éléments dans existingVariations et ajoutez 1
            $newVariation = count($existingVariations) + 1;

            // Appliquez le format '01', '02', etc. avec str_pad
            $newVariation = str_pad($newVariation, 2, '0', STR_PAD_LEFT);
        }

        // Définir le statut à partir du repository
        $status = $this->carpetStatusRepository->findOneBy(['name' => 'Attribué']);
        if ($status) {
            $newOrder->setStatus($status); // Définir le statut si trouvé
        } else {
            throw new Exception('Default status not found.');
        }

        // Générer le nom pour la nouvelle commande
        $name = $this->generateName($newSpecification->getCollection()?->getCode(), $newSpecification->getModel()?->getCode(), $newVariation);

        // Mettre à jour la variation et associer à la commande
        $newOrder->setVariation($name);
        $newOrder->setVariatedFromOrderId($variatedFromOrderId); // Lier la variation à l'ID de la commande parente

        // Persister et flush la nouvelle commande
        $this->carpetDesignOrderRepository->persist($newOrder);
        $this->carpetDesignOrderRepository->flush();

        // Retourner la réponse avec la nouvelle commande
        return new CarpetDesignOrderVariationResponse($newOrder);
    }
    private function cloneRelatedEntities(CarpetSpecification $originalSpecification, CarpetSpecification $newSpecification): void
    {
        // Clone related entities like carpetDimensions, carpetMaterials, designMaterials
        foreach ($originalSpecification->getCarpetDimensions() as $dimension) {
            $newSpecification->addCarpetDimension(clone $dimension);
        }

        foreach ($originalSpecification->getMaterials() as $material) {
            $newSpecification->addMaterial(clone $material);
        }

        foreach ($originalSpecification->getDesignerCompositions() as $designMaterial) {
            $newSpecification->addDesignerComposition(clone $designMaterial);
        }

        // Clone CarpetComposition and its related entities (Layers and Threads)
        $originalComposition = $originalSpecification->getCarpetComposition();
        if ($originalComposition) {
            $newComposition = clone $originalComposition;
            $newComposition->resetUniqueFields(); // Ensure it's treated as a new entity

            // Clone Threads
            foreach ($originalComposition->getThreads() as $thread) {
                $clonedThread = clone $thread;
                $clonedThread->setCarpetComposition($newComposition);
                $newComposition->addThread($clonedThread);
            }
            //clone Layers
            foreach ($originalComposition->getLayers() as $layer) {
                $clonedLayer = clone $layer;
                foreach ($layer->getLayerDetails() as $layerDetail) {
                    $clonedLayerDetail = clone $layerDetail;
                    $clonedLayer->addLayerDetail($clonedLayerDetail);
                }
                $clonedLayer->setCarpetComposition($newComposition);
                $newComposition->addLayer($clonedLayer);
            }

            // Associate the cloned composition with the new specification
            $newSpecification->setCarpetComposition($newComposition);
        }
    }


    private function generateName(?string $collection, ?string $model, ?string $variation): string
    {
        // If the variation is '01', we do not include it in the name.
        if ($variation === '01' || !$variation) {
            // If the model is numeric, include the collection.
            if (is_numeric($model)) {
                return $collection ? "{$collection} {$model}" : "{$model}";
            }
            // If the model is a letter, do not include the collection.
            return "{$model}";
        }

        // If the model is numeric, include the collection and variation.
        if (is_numeric($model)) {
            return $collection ? "{$collection} {$model} {$variation}" : "{$model} {$variation}";
        }

        // If the model is a letter, do not include the collection, just the model and variation.
        return "{$model} {$variation}";
    }
}
