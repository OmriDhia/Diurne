<?php

declare(strict_types=1);

namespace App\ProgressReport\Repository;

use App\Common\Repository\BaseRepository;
use App\ProgressReport\Entity\ProcessDeadline;

interface ProcessDeadlineRepository extends BaseRepository
{
    public function save(ProcessDeadline $deadline, bool $flush = false): void;
}
