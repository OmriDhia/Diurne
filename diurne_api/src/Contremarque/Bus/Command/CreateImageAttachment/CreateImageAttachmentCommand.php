<?php

namespace App\Contremarque\Bus\Command\CreateImageAttachment;

use App\Common\Bus\Command\Command;

class CreateImageAttachmentCommand implements Command
{
    public function __construct(
        public int $imageId,
        public int $attachmentId
    ) {
    }
}
