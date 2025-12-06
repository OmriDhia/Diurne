<?php

declare(strict_types=1);

namespace App\Event\Bus\Query\GetEventsByContremarqueId;

use App\Common\Bus\Query\Query;

/**
 * This class is used for handling the query to retrieve events by contremarque ID.
 */
final readonly class GetEventsByContremarqueIdQuery implements Query
{
    public function __construct(
        public string $contremarqueId
    ) {}
}
