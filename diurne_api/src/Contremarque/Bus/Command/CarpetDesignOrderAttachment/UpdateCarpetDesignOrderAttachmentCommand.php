<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderAttachment;

use App\Common\Bus\Command\Command;

class UpdateCarpetDesignOrderAttachmentCommand implements Command
{
    public function __construct(public int $id, public int $carpetDesignOrderId, public int $attachmentId)
    {
    }
}
