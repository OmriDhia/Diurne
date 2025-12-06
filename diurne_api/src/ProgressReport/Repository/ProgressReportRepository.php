<?php

namespace App\ProgressReport\Repository;

use App\Common\Repository\BaseRepository;
use App\ProgressReport\Entity\ProgressReport;

interface ProgressReportRepository extends BaseRepository
{
    public function save(ProgressReport $progressReport, bool $flush = false): void;
}

