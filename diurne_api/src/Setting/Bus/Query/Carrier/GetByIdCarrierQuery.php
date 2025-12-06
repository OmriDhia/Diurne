<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Carrier;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a carrier by its ID.
 */
final class GetByIdCarrierQuery implements Query
{
    /**
     * Constructor for GetByIdCarrierQuery.
     *
     * @param int $carrierId the unique identifier of the carrier     */
    public function __construct(
        public int $carrierId
    ) {
    }

    public function carrierId(): int
    {
        return $this->carrierId;
    }
}
