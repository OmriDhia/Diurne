<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;

interface CustomerInstructionRepository extends BaseRepository
{
    public function findByCarpetDesignOrder($id);
}
