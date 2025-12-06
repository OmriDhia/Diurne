<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use App\Common\Bus\Command\Command;

class UpdateTechnicalImageCommand implements Command
{
    public function __construct(private readonly int $id, private readonly ?int $imageCommandId, private readonly ?int $imageTypeId, private readonly ?string $name, private readonly ?int $attachmentId)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageCommandId(): int|null
    {
        return $this->imageCommandId;
    }

    public function getImageTypeId(): int|null
    {
        return $this->imageTypeId;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getAttachmentId(): ?int
    {
        return $this->attachmentId;
    }
}