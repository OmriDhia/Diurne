<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;

interface TaxRuleRepository extends BaseRepository
{
    public function findRandomTaxRule();
}
