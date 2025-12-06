<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface ValidatedSampleRepository extends BaseRepository
{
    public function findUnusedValidatedSamples(): array;
}
