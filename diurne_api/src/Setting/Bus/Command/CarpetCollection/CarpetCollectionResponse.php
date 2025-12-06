<?php

// src/Setting/Bus/Command/CarpetCollection/CarpetCollectionResponse.php

namespace App\Setting\Bus\Command\CarpetCollection;

use DateTimeInterface;
use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\CarpetCollection;

final readonly class CarpetCollectionResponse implements CommandResponse
{
    public function __construct(
        private CarpetCollection $carpetCollection
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->carpetCollection->getId(),
            'reference' => $this->carpetCollection->getReference(),
            'code' => $this->carpetCollection->getCode(),
            'collection_group' => $this->carpetCollection->getCarpetGroup(),
            'show_grid' => $this->carpetCollection->isShowGrid(),
            'special_shape' => [
                'id' => $this->carpetCollection->getSpecialShape() ? $this->carpetCollection->getSpecialShape()->getId() : null,
                // Add other properties of SpecialShape as needed
            ],
            'police' => [
                'id' => $this->carpetCollection->getPolice() ? $this->carpetCollection->getPolice()->getId() : null,
                // Add other properties of Police as needed
            ],
            'image_name' => $this->carpetCollection->getImageName(),
            'created_at' => $this->carpetCollection->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updated_at' => $this->carpetCollection->getUpdatedAt()->format(DateTimeInterface::ATOM),
        ];
    }
}
