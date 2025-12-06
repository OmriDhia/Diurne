<?php

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CarpetOrderDetailRepository extends BaseRepository
{
    public function getNextCarpetOrderDetailNumberInQuote($quoteReference);
}