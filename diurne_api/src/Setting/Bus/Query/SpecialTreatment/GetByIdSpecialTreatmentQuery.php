<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\SpecialTreatment;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a specialTreatment by its ID.
 */
final class GetByIdSpecialTreatmentQuery implements Query
{
    /**
     * Constructor for GetByIdSpecialTreatmentQuery.
     *
     * @param int $specialTreatmentId the unique identifier of the specialTreatment     */
    public function __construct(
        public int $specialTreatmentId
    ) {
    }

    public function getSpecialTreatmentId(): int
    {
        return $this->specialTreatmentId;
    }
}
