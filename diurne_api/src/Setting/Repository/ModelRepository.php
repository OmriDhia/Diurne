<?php

namespace App\Setting\Repository;

use App\Setting\Entity\Model;
use App\Common\Repository\BaseRepository;
use App\Setting\Entity\CarpetCollection;

interface ModelRepository extends BaseRepository
{
    public function findOneByCode(string $code): ?Model;
    public function findRandomModelByCollection(CarpetCollection $collection);
}
