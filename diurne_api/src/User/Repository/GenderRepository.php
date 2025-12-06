<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Common\Repository\BaseRepository;

interface GenderRepository extends BaseRepository
{
    public function findByName($name);
    public function selectRandomGender();
}
