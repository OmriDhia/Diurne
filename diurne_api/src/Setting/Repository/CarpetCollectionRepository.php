<?php

// src/App/Setting/Repository/CarpetCollectionRepository.php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\CarpetCollection;

interface CarpetCollectionRepository extends BaseRepository
{
    public function save(CarpetCollection $collectionGroup): void;
    public function findRandomCollection();
}
