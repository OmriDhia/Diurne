<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\ProgressReportProcess;

interface ProgressReportProcessRepository extends BaseRepository
{
    public function save(ProgressReportProcess $process, bool $flush = false): void;
}
