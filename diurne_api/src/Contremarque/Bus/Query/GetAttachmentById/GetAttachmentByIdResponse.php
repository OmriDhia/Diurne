<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetAttachmentById;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\Attachment;

final readonly class GetAttachmentByIdResponse implements QueryResponse
{
    public function __construct(private ?Attachment $attachment)
    {
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function toArray(): array
    {
        return $this->attachment ? [
            'id' => $this->attachment->getId(),
            'filename' => $this->attachment->getFile(),
            'file' => $this->attachment->getFile(),
        ] : [];
    }
}
