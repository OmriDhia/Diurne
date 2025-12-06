<?php

declare(strict_types=1);

namespace App\Contremarque\Service\CheckSpecificationCoherence;

use App\Contremarque\Bus\Query\CheckSpecificationCoherence\CheckSpecificationCoherenceResponse;
use App\Contremarque\Entity\CarpetDesignOrder;
use App\Contremarque\Entity\QuoteDetail;


class CheckSpecificationCoherence
{
    public function check(CarpetDesignOrder $carpetDesignOrder, QuoteDetail $quoteDetail): CheckSpecificationCoherenceResponse
    {
        $designOrderSpec = $carpetDesignOrder->getCarpetSpecification();
        $quoteDetailSpec = $quoteDetail->getCarpetSpecification();

        $differences = [];
        $isCoherent = true;

        // Compare collection
        $differences['collection'] = [
            'carpetDesignOrder' => $designOrderSpec->getCollection()->getReference(),
            'quoteDetail' => $quoteDetailSpec->getCollection()->getReference(),
        ];

        if ($designOrderSpec->getCollection()->getId() !== $quoteDetailSpec->getCollection()->getId()) {
            $isCoherent = false;
        }

        // Compare quality
        $differences['quality'] = [
            'carpetDesignOrder' => $designOrderSpec->getQuality()->getName(),
            'quoteDetail' => $quoteDetailSpec->getQuality()->getName(),
        ];
        if ($designOrderSpec->getQuality()->getId() !== $quoteDetailSpec->getQuality()->getId()) {
            $isCoherent = false;
        }

        // Compare model
        $differences['model'] = [
            'carpetDesignOrder' => $designOrderSpec->getModel()->getCode(),
            'quoteDetail' => $quoteDetailSpec->getModel()->getCode(),
        ];
        if ($designOrderSpec->getModel()->getId() !== $quoteDetailSpec->getModel()->getId()) {
            $isCoherent = false;
        }

        // Compare dimensions
        $designOrderDimensionsValues = $this->extractDimensions($designOrderSpec);
        $quoteDetailDimensionsValues = $this->extractDimensions($quoteDetailSpec);

        $differences['dimensions'] = [
            'carpetDesignOrder' => $designOrderDimensionsValues,
            'quoteDetail' => $quoteDetailDimensionsValues,
        ];
        if ($designOrderDimensionsValues != $quoteDetailDimensionsValues) {
            $isCoherent = false;
        }

        // Compare materials
        $designOrderMaterialsValues = $this->extractMaterials($designOrderSpec);
        $quoteDetailMaterialsValues = $this->extractMaterials($quoteDetailSpec);

        $differences['materials'] = [
            'carpetDesignOrder' => $designOrderMaterialsValues,
            'quoteDetail' => $quoteDetailMaterialsValues
        ];
        if ($designOrderMaterialsValues != $quoteDetailMaterialsValues) {
            $isCoherent = false;
        }

        // Compare location
        $differences['location'] = [
            'carpetDesignOrder' => $carpetDesignOrder->getLocation()->getId(),
            'quoteDetail' => $quoteDetail->getLocation()->getId(),
        ];
        if ($carpetDesignOrder->getLocation()->getId() !== $quoteDetail->getLocation()->getId()) {
            $isCoherent = false;
        }

        return new CheckSpecificationCoherenceResponse($isCoherent, $differences);
    }

    private function extractDimensions($carpetDimensions): array
    {
        $dimensions = [];

        foreach ($carpetDimensions->getCarpetDimensions() as $carpetDimension) {
            $measurement = $carpetDimension->getMesurement();
            if (!$measurement) {
                continue;
            }
            foreach ($carpetDimension->getDimensionValues() as $index => $dimensionValue) {
                if ($dimensionValue && $dimensionValue->getUnit()?->getId() == 1) {
                    $dimensions[$measurement->getName()][$index] = [
                        'unit_id' => $dimensionValue->getUnit()?->getId() ?? 0,
                        'unit_name' => $dimensionValue->getUnit()?->getName(),
                        'unit_abbreviation' => $dimensionValue->getUnit()?->getAbbreviation(),
                        'value' => $dimensionValue->getValue(),
                    ];
                }
            }
        }

        return $dimensions;
    }

    private function extractMaterials($quoteDetail): array
    {
        $materials = [];
        foreach ($quoteDetail->getMaterials() as $material) {
            $materials[$material->getMaterial()->getId()] = [
                'material_id' => $material->getMaterial()->getId(),
                'reference' => $material->getMaterial()->getReference(),
                'rate' => $material->getRate(),
            ];
        }
        return $materials;
    }
}