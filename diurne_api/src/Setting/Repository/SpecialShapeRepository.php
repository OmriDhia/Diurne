<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;
use App\Setting\Entity\SpecialShape;

interface SpecialShapeRepository extends BaseRepository
{
    public function findOneByLabel(string $label): ?SpecialShape;
    public function findRandomSpecialShape();
}
