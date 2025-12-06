<?php

namespace App\ProgressReport\Repository;

use App\Common\Repository\BaseRepository;
use App\ProgressReport\Entity\ProgressReportStatus;

interface ProgressReportStatusRepository extends BaseRepository
{
    public function save(ProgressReportStatus $status, bool $flush = false): void;
}

