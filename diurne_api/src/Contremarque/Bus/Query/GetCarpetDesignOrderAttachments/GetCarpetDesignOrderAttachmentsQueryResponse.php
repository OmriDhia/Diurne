<?php

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderAttachments;

use App\Common\Bus\Query\QueryResponse;
use App\Contremarque\Entity\CarpetDesignOrderAttachment;

class GetCarpetDesignOrderAttachmentsQueryResponse implements QueryResponse
{
    public function __construct(private readonly array $attachments)
    {
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function toArray(): array
    {
        return array_map(fn (CarpetDesignOrderAttachment $attachment) => $attachment->toArray(), $this->attachments);
    }
}
