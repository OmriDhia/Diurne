<?php

namespace App\Contremarque\Bus\Command\AttachmentUpload;

use App\Common\Bus\Command\Command;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileCommand implements Command
{
    public function __construct(
        private readonly ?UploadedFile $file = null,
        private readonly ?string $distantFilePath = null
    ) {
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function getDistantFilePath(): ?string
    {
        return $this->distantFilePath;
    }

    public function setAttachmentTypeId($attachmentTypeId): static
    {
        $this->attachmentTypeId = $attachmentTypeId;

        return $this;
    }

    public function getAttachmentTypeId(): int
    {
        return $this->attachmentTypeId;
    }
}
