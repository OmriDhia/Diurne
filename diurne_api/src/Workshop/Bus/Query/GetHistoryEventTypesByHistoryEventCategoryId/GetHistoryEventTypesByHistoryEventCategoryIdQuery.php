<?php
declare(strict_types=1);

namespace App\Workshop\Bus\Query\GetHistoryEventTypesByHistoryEventCategoryId;

use App\Common\Bus\Query\Query;

final readonly class GetHistoryEventTypesByHistoryEventCategoryIdQuery implements Query
{
    public function __construct(public int $historyEventCategoryId)
    {
    }
}
