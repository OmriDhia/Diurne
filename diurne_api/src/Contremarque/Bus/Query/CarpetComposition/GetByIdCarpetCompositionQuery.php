<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a carpetComposition by its ID.
 */
final class GetByIdCarpetCompositionQuery implements Query
{
    /**
     * Constructor for GetByIdCarpetCompositionQuery.
     *
     * @param int $carpetCompositionId the unique identifier of the carpetComposition     */
    public function __construct(
        public int $carpetCompositionId
    ) {
    }

    public function carpetCompositionId(): int
    {
        return $this->carpetCompositionId;
    }
}
