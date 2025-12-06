<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Color;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a color by its ID.
 */
final class GetByIdColorQuery implements Query
{
    /**
     * Constructor for GetByIdColorQuery.
     *
     * @param int $colorId the unique identifier of the color     */
    public function __construct(
        public int $colorId
    ) {
    }

    public function colorId(): int
    {
        return $this->colorId;
    }
}
