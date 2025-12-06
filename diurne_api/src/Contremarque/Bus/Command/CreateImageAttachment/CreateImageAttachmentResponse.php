<?php

namespace App\Contremarque\Bus\Command\CreateImageAttachment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\ImageAttachment;

class CreateImageAttachmentResponse implements CommandResponse
{
    public function __construct(private readonly ImageAttachment $imageAttachment)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->imageAttachment->getId(),
            'imageId' => $this->imageAttachment->getImage()?->getId(),
            'attachmentId' => $this->imageAttachment->getAttachment()?->getId(),
        ];
    }
}
