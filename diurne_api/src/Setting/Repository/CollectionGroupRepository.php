<?php

// src/App/Setting/Repository/CollectionGroupRepository.php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\CollectionGroup;

interface CollectionGroupRepository extends BaseRepository
{
    public function save(CollectionGroup $collectionGroup): void;
    // Add more methods as needed
}
