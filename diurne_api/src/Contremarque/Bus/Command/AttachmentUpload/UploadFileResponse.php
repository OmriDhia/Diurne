<?php

namespace App\Contremarque\Bus\Command\AttachmentUpload;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\Attachment;

class UploadFileResponse implements CommandResponse
{
    public function __construct(
        private readonly Attachment $attachment
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->attachment->getId(),
            'filename' => $this->attachment->getFile(),
            'path' => $this->attachment->getPath(),
            'fromDistantServer' => $this->attachment->isFromDistantServer(),
            'attachmentType' => $this->attachment->getAttachmentType()->toArray(),
        ];
    }
}
