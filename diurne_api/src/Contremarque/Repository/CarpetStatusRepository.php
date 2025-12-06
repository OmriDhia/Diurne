<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CarpetStatusRepository extends BaseRepository
{
    public function getStatusByName($name);
}
