<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetDesignOrder;

use DateTimeInterface;
use App\Common\Bus\Command\Command;

class UpdateCarpetDesignOrderCommand implements Command
{
    public ?DateTimeInterface $transmition_date = null;

    public function __construct(
        private readonly int    $id,
        private readonly ?int   $locationId = null,
        private readonly ?array $designerAssignments = null,
        private readonly ?int   $statusId = null,
        private readonly ?string $modelName = null,
        private readonly ?string $variation = null,
        private readonly ?bool $jpeg = null,
        private readonly ?bool $impression = null,
        private readonly ?bool $impressionBarreDeLaine = null,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTransmitionDate(): ?DateTimeInterface
    {
        return $this->transmition_date;
    }

    public function setTransmitionDate(?DateTimeInterface $transmition_date): void
    {
        $this->transmition_date = $transmition_date;
    }

    public function getLocationId(): ?int
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
