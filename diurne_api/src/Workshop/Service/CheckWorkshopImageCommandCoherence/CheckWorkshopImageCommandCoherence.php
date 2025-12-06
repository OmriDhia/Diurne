<?php

declare(strict_types=1);

namespace App\Workshop\Service\CheckWorkshopImageCommandCoherence;

use App\Contremarque\Bus\Query\CheckSpecificationCoherence\CheckSpecificationCoherenceResponse;
use App\Contremarque\Entity\ImageCommand\ImageCommand;
use App\Workshop\Entity\WorkshopOrder;

use App\Setting\Repository\MaterialRepository;

class CheckWorkshopImageCommandCoherence
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
    ) {
    }
    public function check(WorkshopOrder $workshopOrder, ImageCommand $imageCommand): CheckSpecificationCoherenceResponse
    {
        $workshopInfo = $workshopOrder->getWorkshopInformation();
        $carpetSpec = $imageCommand->getCarpetDesignOrder()?->getCarpetSpecification();

        $differences = [];
        $isCoherent = true;

        if ($workshopInfo && $carpetSpec) {
            // Quality check
            $differences['quality'] = [
                'workshopOrder' => $workshopInfo->getQuality()?->getName(),
                'imageCommand' => $carpetSpec->getQuality()?->getName(),
            ];
            if ($workshopInfo->getQuality()?->getId() !== $carpetSpec->getQuality()?->getId()) {
                $isCoherent = false;
            }

            // Materials check
            $workshopMaterials = $this->extractWorkshopMaterials($workshopInfo);
            $specMaterials = $this->extractSpecMaterials($carpetSpec);
            $differences['materials'] = [
                'workshopOrder' => $workshopMaterials,
                'imageCommand' => $specMaterials,
            ];
            if ($workshopMaterials != $specMaterials) {
                $isCoherent = false;
            }

            // Dimensions check
            $workshopDimensions = [
                'width' => $workshopInfo->getOrderedWidth(),
                'height' => $workshopInfo->getOrderedHeigh(),
            ];
            $specDimensions = $this->extractDimensions($carpetSpec);
            $differences['dimensions'] = [
                'workshopOrder' => $workshopDimensions,
                'imageCommand' => $specDimensions,
            ];
            if ($workshopDimensions != $specDimensions) {
                $isCoherent = false;
            }
        }

        return new CheckSpecificationCoherenceResponse($isCoherent, $differences);
    }

    private function extractWorkshopMaterials($workshopInfo): array
    {
        $materials = [];
        foreach ($workshopInfo->getMaterialPurchasePrices() as $mpp) {
            $materialId = $mpp->getMaterialId();
            $material = $this->materialRepository->find($materialId);

            $materials[$materialId] = [
                'material_id' => $materialId,
                'name' => $material?->getReference(),
            ];
        }
        ksort($materials);
        return $materials;
    }

    private function extractSpecMaterials($spec): array
    {
        $materials = [];
        foreach ($spec->getMaterials() as $material) {
            $materials[$material->getMaterial()->getId()] = [
                'material_id' => $material->getMaterial()->getId(),
                'name' => $material->getMaterial()->getReference(),
            ];
        }
        ksort($materials);
        return $materials;
    }

    private function extractDimensions($spec): array
    {
        $dimensions = [];
        foreach ($spec->getCarpetDimensions() as $dimension) {
            $measurement = $dimension->getMesurement();
            if (!$measurement) {
                continue;
            }
            foreach ($dimension->getDimensionValues() as $index => $value) {
                if ($value && $value->getUnit()?->getId() == 1) {
                    $dimensions[$measurement->getName()][$index] = [
                        'unit_id' => $value->getUnit()?->getId() ?? 0,
                        'unit_name' => $value->getUnit()?->getName(),
                        'unit_abbreviation' => $value->getUnit()?->getAbbreviation(),
                        'value' => $value->getValue(),
                    ];
                }
            }
        }
        return $dimensions;
    }
}
