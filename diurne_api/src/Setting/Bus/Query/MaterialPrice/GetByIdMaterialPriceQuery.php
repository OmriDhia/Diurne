<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\MaterialPrice;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a materialPrice by its ID.
 */
final class GetByIdMaterialPriceQuery implements Query
{
    /**
     * Constructor for GetByIdMaterialPriceQuery.
     *
     * @param int $materialId the unique identifier of the Material     */
    public function __construct(
        public int $materialId
    ) {
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }
}
