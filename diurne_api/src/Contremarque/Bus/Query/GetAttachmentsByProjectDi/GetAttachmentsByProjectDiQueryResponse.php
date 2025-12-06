<?php

namespace App\Contremarque\Bus\Query\GetAttachmentsByProjectDi;

use App\Common\Bus\Query\QueryResponse;

class GetAttachmentsByProjectDiQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $attachments)
    {
    }

    public function toArray(): array
    {
        return array_map(fn($attachment) => $attachment->toArray(), $this->attachments);
    }
}
