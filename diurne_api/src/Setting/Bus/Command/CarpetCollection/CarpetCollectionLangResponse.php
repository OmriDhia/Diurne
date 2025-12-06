<?php

// src/Setting/Bus/Command/CarpetCollection/CarpetCollectionLangResponse.php

namespace App\Setting\Bus\Command\CarpetCollection;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\CarpetCollectionLang;

final readonly class CarpetCollectionLangResponse implements CommandResponse
{
    public function __construct(
        private CarpetCollectionLang $carpetCollectionLang
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->carpetCollectionLang->getId(),
            'description' => $this->carpetCollectionLang->getDescription(),
            'carpet_collection_id' => $this->carpetCollectionLang->getCarpetCollection()->getId(),
            'language' => [
                'id' => $this->carpetCollectionLang->getLanguage()->getId(),
                'name' => $this->carpetCollectionLang->getLanguage()->getName(),
                'iso_code' => $this->carpetCollectionLang->getLanguage()->getIsoCode(),
            ],
        ];
    }
}
