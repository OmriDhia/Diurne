<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CustomerConstraintRepository extends BaseRepository
{
    public function findUnusedConstraints(): array;
}
