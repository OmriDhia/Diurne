<?php

namespace App\Contremarque\Bus\Query\ImageCommandDesignerAssignment;

use App\Common\Bus\Query\QueryResponse;

class GetImageCommandDesignerAssignmentResponse implements QueryResponse
{
    public function __construct(public array $image)
    {
    }

    public function toArray(): array
    {
        return ['imageCommandDesignerAttachment' => $this->image];
    }
}