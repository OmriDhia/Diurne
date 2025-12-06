<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\CarpetPriceBase;
use App\Contremarque\Entity\Location;
use App\Contremarque\Entity\Quote;
use App\Contremarque\Entity\QuoteDetail;
use App\Setting\Entity\PriceType;

interface QuoteDetailRepository extends BaseRepository
{
    public function findOneById(int $id): ?QuoteDetail;

    public function create(array $data);

    public function update($entity, array $data);

    public function getQuoteDetailPrice(QuoteDetail $quoteDetail, PriceType $priceType): ?CarpetPriceBase;

    public function getNextCarpetNumberInQuote($quoteReference);

    public function getAllQuoteDetails(Quote $quote);

    public function save(QuoteDetail $quoteDetail, bool $flush = true): void;

    public function findByCarpetDesignOrder($carpetDesignOrder);
    public function hasAssociatedQuoteDetails(Location $location): bool;
}
