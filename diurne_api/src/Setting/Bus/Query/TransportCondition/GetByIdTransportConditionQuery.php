<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportCondition;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a transportCondition by its ID.
 */
final class GetByIdTransportConditionQuery implements Query
{
    /**
     * Constructor for GetByIdTransportConditionQuery.
     *
     * @param int $transportConditionId the unique identifier of the transportCondition     */
    public function __construct(
        public int $transportConditionId
    ) {
    }

    public function getTransportConditionId(): int
    {
        return $this->transportConditionId;
    }
}
