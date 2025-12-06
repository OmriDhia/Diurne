<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetDesignerAssignments;

use App\Common\Bus\Query\Query;

final class GetDesignerAssignmentsQuery implements Query
{
    public function __construct(public int $carpetDesignOrderId)
    {
    }
}
