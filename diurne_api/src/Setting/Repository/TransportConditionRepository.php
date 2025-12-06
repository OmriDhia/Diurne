<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;

interface TransportConditionRepository extends BaseRepository
{
    public function findRandomTransportCondition();
}
