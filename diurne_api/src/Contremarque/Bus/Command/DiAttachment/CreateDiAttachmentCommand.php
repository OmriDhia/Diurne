<?php

namespace App\Contremarque\Bus\Command\DiAttachment;

use App\Common\Bus\Command\Command;

class CreateDiAttachmentCommand implements Command
{
    public function __construct(public int $attachmentId, public int $diId)
    {
    }
}
