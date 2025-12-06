<?php
declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\HistoryEventType;
use App\Workshop\Entity\HistoryEventCategory;

interface HistoryEventTypeRepository extends BaseRepository
{
    public function findOneByName(array $criteria): ?HistoryEventType;

    /**
     * @param HistoryEventCategory $category
     * @return HistoryEventType[]
     */
    public function findByHistoryEventCategory(HistoryEventCategory $category): array;
}
