<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Manufacturer;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a manufacturer by its ID.
 */
final class GetByIdManufacturerQuery implements Query
{
    /**
     * Constructor for GetByIdManufacturerQuery.
     *
     * @param int $manufacturerId the unique identifier of the manufacturer     */
    public function __construct(
        public int $manufacturerId
    ) {
    }

    public function manufacturerId(): int
    {
        return $this->manufacturerId;
    }
}
