<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Quality;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\Quality;

class QualityQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $qualities) {}

    public function toArray(): array
    {
        $response = [];
        foreach ($this->qualities as $quality) {
            $response[] = [
                'id' => $quality->getId(),
                'name' => $quality->getName(),
                'weight' => $quality->getWeight(),
                'velvet_standard' => $quality->getVelvetStandard(),
                'descriptions' => $this->getDescriptions($quality),
            ];
        }

        return $response;
    }

    private function getDescriptions(Quality $quality): array
    {
        $descriptions = [];
        foreach ($quality->getQualityLangs() as $qualityLang) {
            $descriptions[] = $qualityLang->toArray();
        }

        return $descriptions;
    }
}
