<?php
declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\Carpet;


interface CarpetRepository extends BaseRepository
{
    public function getNextRnNumber(int $manufacturerId): string;
}