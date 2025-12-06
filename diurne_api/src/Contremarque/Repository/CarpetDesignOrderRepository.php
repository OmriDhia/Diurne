<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\Location;
use App\Contremarque\Entity\QuoteDetail;

interface CarpetDesignOrderRepository extends BaseRepository
{
    public function getCarpetDesignOrderOptions(QuoteDetail $quoteDetail): array;

    public function getImageTypeNames(int $carpetId): array;

    public function findByContremarqueAndProjectDi(int $contremarqueId, int $projectDiId): array;

    public function hasAssociatedCarpetDesignOrders(Location $location): bool;

    public function createQueryBuilderForFixture(string $string);
}
