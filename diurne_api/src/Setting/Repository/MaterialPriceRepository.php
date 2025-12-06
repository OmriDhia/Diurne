<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;

use App\Setting\Entity\Material;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Entity\MaterialPrice;

interface MaterialPriceRepository extends BaseRepository
{
    public function findOneByQualityTarifTextureAndMaterial(QualityTarifTexture $qualityTarifTexture, Material $material): ?MaterialPrice;
}
