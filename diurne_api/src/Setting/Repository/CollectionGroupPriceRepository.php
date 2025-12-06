<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\CollectionGroup;
use App\Setting\Entity\CollectionGroupPrice;
use App\Setting\Entity\TarifGroup;

interface CollectionGroupPriceRepository extends BaseRepository
{
    public function create(array $data);

    public function update($entity, array $data);

    public function findMinPrice(CollectionGroup $collectionGroup, TarifGroup $tarifGroup): ?string;

    public function findMaxPrice(CollectionGroup $collectionGroup, TarifGroup $tarifGroup): ?string;
}
