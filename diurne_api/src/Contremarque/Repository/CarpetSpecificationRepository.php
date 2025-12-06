<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use DateTimeInterface;
use App\Common\Repository\BaseRepository;

interface CarpetSpecificationRepository extends BaseRepository
{
    public function getNextReference(?DateTimeInterface $date = null): string;
}
