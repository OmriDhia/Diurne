<?php

declare(strict_types=1);

namespace App\User\Bus\Query\HasPermissionTo;

use App\Common\Bus\Query\QueryResponse;

final class HasPermissionToResponse implements QueryResponse
{
    /**
     * HasPermissionToResponse constructor.
     */
    public function __construct(
        public bool $status,
    ) {}
    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
