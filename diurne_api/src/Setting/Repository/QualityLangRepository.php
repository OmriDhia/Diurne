<?php

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\QualityLang;

interface QualityLangRepository extends BaseRepository
{
    public function save(QualityLang $quality): void;
    // Add more methods as needed
}
