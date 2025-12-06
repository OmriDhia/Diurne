<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDesignerAssignments;

use App\Common\Bus\Query\QueryResponse;

final class GetDesignerAssignmentsResponse implements QueryResponse
{
    public function __construct(public array $designerAssignments)
    {
    }

    public function toArray(): array
    {
        return [
            'designerAssignments' => $this->designerAssignments,
        ];
    }
}
