<?php

namespace App\Setting\Bus\Command\Material;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\Material;

class MaterialResponse implements CommandResponse
{
    public function __construct(private readonly Material $material)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->material->getId(),
            'reference' => $this->material->getReference(),
            'descriptions' => $this->getDescriptions($this->material),
        ];
    }

    private function getDescriptions(Material $material): array
    {
        $descriptions = [];
        foreach ($material->getMaterialLangs() as $materialLang) {
            $descriptions[] = [
                'language_id' => $materialLang->getLanguage()->getId(),
                'label' => $materialLang->getLabel(),
            ];
        }

        return $descriptions;
    }
}
