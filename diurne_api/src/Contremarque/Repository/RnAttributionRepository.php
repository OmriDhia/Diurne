<?php

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\CarpetOrder\CarpetOrderDetail;
use App\Contremarque\Entity\CarpetOrder\RnAttribution;

interface RnAttributionRepository extends BaseRepository
{
    public function findLastCanceledByCarpetOrderDetail(CarpetOrderDetail $carpetOrderDetail): ?RnAttribution;
}