<?php

declare(strict_types=1);

namespace App\Event\Repository;

use App\Common\Repository\BaseRepository;

interface PeoplePresentRepository extends BaseRepository
{
    public function findByEventIds(array $eventIds): array;
}
