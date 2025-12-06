<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\GetAllMaterials;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Material;

class MaterialQueryResponse implements QueryResponse
{
    /**
     * @param array<int, array<string, mixed>|Material> $materials
     */
    public function __construct(private readonly array $materials, private readonly int $totalItems, private readonly ?int $page, private readonly int $itemsPerPage)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $materialsData = [];
        foreach ($this->materials as $material) {
            if ($material instanceof Material) {
                $materialsData[] = [
                    'id' => $material->getId(),
                    'reference' => $material->getReference(),
                    'descriptions' => $this->getDescriptions($material),
                ];
            } else {
                $materialsData[] = $material; // Already an array from cache
            }
        }

        return [
            'data' => $materialsData,
            'meta' => [
                'total_items' => $this->totalItems,
                'page' => $this->page ?? 1,
                'items_per_page' => $this->itemsPerPage,
            ],
        ];
    }

    /**
     * Maps MaterialLang entities to an array of descriptions.
     *
     * @param Material $material
     * @return array<int, array<string, mixed>>
     */
    private function getDescriptions(Material $material): array
    {
        $descriptions = [];
        foreach ($material->getMaterialLangs() as $index => $materialLang) {
            $descriptions[$index]['id_lang'] = $materialLang->getLanguage()->getId();
            $descriptions[$index]['iso_code'] = $materialLang->getLanguage()->getIsoCode();
            $descriptions[$index]['label'] = $materialLang->getLabel();
        }

        return $descriptions;
    }
}
