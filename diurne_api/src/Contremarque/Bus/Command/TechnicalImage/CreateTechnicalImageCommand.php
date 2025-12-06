<?php

namespace App\Contremarque\Bus\Command\TechnicalImage;

use App\Common\Bus\Command\Command;

class CreateTechnicalImageCommand implements Command
{
    public function __construct(private readonly int $imageCommandId, private readonly int $imageTypeId, private readonly string $name, private readonly int $attachmentId)
    {
    }

    public function getImageCommandId(): int
    {
        return $this->imageCommandId;
    }

    public function getImageTypeId(): int
    {
        return $this->imageTypeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }
}