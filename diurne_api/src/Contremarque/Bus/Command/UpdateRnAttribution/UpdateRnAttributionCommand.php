<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Command\UpdateRnAttribution;

use App\Common\Bus\Command\Command;

class UpdateRnAttributionCommand implements Command
{
    /**
     * @param int $id
     * @param string|null $rn
     * @param string|null $attributedAt
     * @param string|null $canceledAt
     */
    public function __construct(
        public readonly int     $id,
        public readonly ?string $rn = null,
        public readonly ?string $attributedAt = null,
        public readonly ?string $canceledAt = null
    )
    {
    }
}