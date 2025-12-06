<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface QuoteRepository extends BaseRepository
{
    public function getNextQuoteNumber(): string;
    public function getQuoteRows(int $quoteId): array;
}
