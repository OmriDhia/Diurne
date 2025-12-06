<?php
declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\HistoryEventCategory;

interface HistoryEventCategoryRepository extends BaseRepository
{
    public function findOneByName(array $criteria): ?HistoryEventCategory;

}