<?php

namespace App\Setting\Bus\Command\CarpetCollection;

use App\Common\Bus\Command\Command;

class UpdateCarpetCollectionCommand implements Command
{
    public function __construct(
        private readonly int     $id,
        private readonly string  $reference,
        private readonly string  $code,
        private readonly int     $collectionGroupId,
        private readonly bool    $showGrid,
        private readonly ?int    $specialShapeId,
        private readonly ?int    $policeId,
        private readonly ?string $imageName,
        private readonly ?int    $authorId,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCollectionGroupId(): int
    {
        return $this->collectionGroupId;
    }

    public function isShowGrid(): bool
    {
        return $this->showGrid;
    }

    public function getSpecialShapeId(): ?int
    {
        return $this->specialShapeId;
    }

    public function getPoliceId(): ?int
    {
        return $this->policeId;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }
}
