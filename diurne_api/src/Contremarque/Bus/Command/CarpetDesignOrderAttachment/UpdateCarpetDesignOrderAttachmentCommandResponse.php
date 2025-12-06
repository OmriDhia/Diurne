<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderAttachment;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;

class UpdateCarpetDesignOrderAttachmentCommandResponse implements CommandResponse
{
    public function __construct(private readonly CarpetDesignOrderAttachment $carpetDesignOrderAttachment)
    {
    }

    public function getCarpetDesignOrderAttachmentId(): int|null
    {
        return $this->carpetDesignOrderAttachment->getId();
    }

    public function toArray(): array
    {
        return $this->carpetDesignOrderAttachment->toArray();
    }
}
