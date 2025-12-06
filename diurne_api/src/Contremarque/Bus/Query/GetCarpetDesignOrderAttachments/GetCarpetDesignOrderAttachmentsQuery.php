<?php

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderAttachments;

use App\Common\Bus\Query\Query;

class GetCarpetDesignOrderAttachmentsQuery implements Query
{
    public function __construct(private readonly int $carpetDesignOrderId)
    {
    }

    public function getCarpetDesignOrderId(): int
    {
        return $this->carpetDesignOrderId;
    }
}
