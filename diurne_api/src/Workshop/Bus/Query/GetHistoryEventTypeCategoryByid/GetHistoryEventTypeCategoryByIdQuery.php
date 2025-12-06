<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypeCategoryByid;


use App\Common\Bus\Query\Query;

class GetHistoryEventTypeCategoryByIdQuery implements Query
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