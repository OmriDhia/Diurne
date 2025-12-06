<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\DiscountRule;

interface TarifRepository extends BaseRepository
{
    public function getRandomTarif();
    public function getLastTarifByDiscountRule(DiscountRule $discountRule);
}
