<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\Contremarque;

interface LocationRepository extends BaseRepository
{
    public function getLastModifiedOrder($location);
    public function findRandomLocationByContremarque(Contremarque $contremarque);
}
