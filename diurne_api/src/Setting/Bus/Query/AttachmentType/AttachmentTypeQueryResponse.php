<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\AttachmentType;

use App\Common\Bus\Query\QueryResponse;
use App\Setting\Entity\AttachmentType;

class AttachmentTypeQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $attachmentTypes)
    {
    }

    public function toArray(): array
    {
        // Format each attachment type entity into an array
        return array_map(fn(AttachmentType $attachmentType) => [
            'id' => $attachmentType->getId(),
            'name' => $attachmentType->getName(),
        ], $this->attachmentTypes);
    }
}
