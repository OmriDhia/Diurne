<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrder;

use App\Common\Bus\Command\Command;

class CreateCarpetDesignOrderCommand implements Command
{

    public function __construct(
        private readonly int    $projectDiId,
        private readonly int    $locationId,
        private readonly ?array $designerAssignments = [],
        private readonly ?int   $statusId = null,
        private readonly ?string $modelName = null,
        private readonly ?string $variation = null,
        private readonly ?bool $jpeg = null,
        private readonly ?bool $impression = null,
        private readonly ?bool $impressionBarreDeLaine = null,
    )
    {
    }


    public function getProjectDiId(): int
    {
        return $this->projectDiId;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function getDesignerAssignments(): ?array
    {
        return $this->designerAssignments;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function getVariation(): ?string
    {
        return $this->variation;
    }

    public function getJpeg(): ?bool
    {
        return $this->jpeg;
    }

    public function getImpression(): ?bool
    {
        return $this->impression;
    }

    public function getImpressionBarreDeLaine(): ?bool
    {
        return $this->impressionBarreDeLaine;
    }
}
