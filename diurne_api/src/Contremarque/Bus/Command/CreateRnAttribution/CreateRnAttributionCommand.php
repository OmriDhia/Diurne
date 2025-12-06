<?php

namespace App\Contremarque\Bus\Command\CreateRnAttribution;

use App\Common\Bus\Command\Command;

class CreateRnAttributionCommand implements Command
{
    /**
     * @param int $carpetOrderDetailId
     * @param int $carpetId
     * @param string|null $attributedAt
     * @param string|null $canceledAt
     */
    public function __construct(
        public readonly int     $carpetOrderDetailId,
        public readonly int     $carpetId,
        public readonly ?string $attributedAt = null,
        public readonly ?string $canceledAt = null
    )
    {
    }
}