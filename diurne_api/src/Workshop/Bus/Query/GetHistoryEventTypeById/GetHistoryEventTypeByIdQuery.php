<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeById;


use App\Common\Bus\Query\Query;

class GetHistoryEventTypeByIdQuery implements Query
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id
    )
    {
    }
}