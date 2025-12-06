<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderAttachment;

use App\Common\Bus\Command\Command;

class CreateCarpetDesignOrderAttachmentCommand implements Command
{
    public function __construct(public int $carpetDesignOrderId, public int $attachmentId)
    {
    }
}
