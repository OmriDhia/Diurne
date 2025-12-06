<?php

namespace App\Setting\Bus\Command\AttachmentType;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\AttachmentType;

class AttachmentTypeResponse implements CommandResponse
{
    public function __construct(private readonly AttachmentType $attachmentType)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->attachmentType->getId(),
            'name' => $this->attachmentType->getName(),
        ];
    }
}
