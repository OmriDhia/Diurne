<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\Quality;
use App\Setting\Entity\QualityTarifTexture;
use App\Setting\Entity\TarifTexture;

interface QualityTarifTextureRepository extends BaseRepository
{
    public function findByQualityAndTarifTexture(Quality $quality, TarifTexture $tarifTexture): ?QualityTarifTexture;
}
