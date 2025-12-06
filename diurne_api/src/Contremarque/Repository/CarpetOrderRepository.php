<?php

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CarpetOrderRepository extends BaseRepository
{
    public function getNextCarpetOrderNumber(): string;
}