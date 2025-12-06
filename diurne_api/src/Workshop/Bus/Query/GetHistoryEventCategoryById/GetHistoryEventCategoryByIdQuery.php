<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventCategoryById;


use App\Common\Bus\Query\Query;

class GetHistoryEventCategoryByIdQuery implements Query
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