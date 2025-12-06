<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\TransportType;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a transportType by its ID.
 */
final class GetByIdTransportTypeQuery implements Query
{
    /**
     * Constructor for GetByIdTransportTypeQuery.
     *
     * @param int $transportTypeId the unique identifier of the transportType     */
    public function __construct(
        public int $transportTypeId
    ) {
    }

    public function transportTypeId(): int
    {
        return $this->transportTypeId;
    }
}
