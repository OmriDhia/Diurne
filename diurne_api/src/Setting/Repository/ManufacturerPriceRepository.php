<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\ManufacturerPrice;
use App\Setting\Entity\ManufacturerPriceGrid;
use App\Setting\Entity\Material;

interface ManufacturerPriceRepository extends BaseRepository
{
    public function findOneByGridAndMaterial(ManufacturerPriceGrid $priceGrid, Material $material): ?ManufacturerPrice;
}
