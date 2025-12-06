<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\Quality;
use App\Setting\Entity\TarifTexture;

interface QualityRepository extends BaseRepository
{
    public function save(Quality $quality): void;
    public function  getRandomQualityWithTarifTexture(TarifTexture $tarifTexture);
}
