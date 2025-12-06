<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\CarpetComposition\Thread;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a thread by its ID.
 */
final class GetThreadsByCarpetCompositionIdQuery implements Query
{
    /**
     * Constructor for GetByIdThreadQuery.
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
