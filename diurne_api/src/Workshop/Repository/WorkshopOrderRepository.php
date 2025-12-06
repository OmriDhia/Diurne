<?php
declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\WorkshopOrder;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface WorkshopOrderRepository extends BaseRepository
{
    public function findByCriteria(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): Paginator;
}