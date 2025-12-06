<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetCarpetDesignOrderById;

use App\Common\Bus\Query\Query;

class GetCarpetDesignOrderByIdQuery implements Query
{
    public function __construct(
        private readonly int $id,
        private readonly bool $forceRefresh = false
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function isForceRefresh(): bool
    {
        return $this->forceRefresh;
    }
}
