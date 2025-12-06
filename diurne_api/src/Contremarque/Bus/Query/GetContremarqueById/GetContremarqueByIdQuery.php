<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetContremarqueById;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve a contremarque by its ID.
 */
final class GetContremarqueByIdQuery implements Query
{
    /**
     * Constructor for GetContremarqueByIdQuery.
     *
     * @param int $contremarqueId the unique identifier of the contremarque
     */
    public function __construct(
        public int $contremarqueId
    ) {
    }

    public function contremarqueId(): int
    {
        return $this->contremarqueId;
    }
}
