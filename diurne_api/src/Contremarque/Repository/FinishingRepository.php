<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface FinishingRepository extends BaseRepository
{
    public function findUnusedFinitions(): array;
}
