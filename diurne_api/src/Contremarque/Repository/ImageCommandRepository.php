<?php

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\ImageCommand\ImageCommand;

interface ImageCommandRepository extends BaseRepository
{
    public function findByDesigner(int $designerId);

    public function findOneByCarpetDesignOrder($id): ?ImageCommand;

    public function findAllImageCommands(): array;
}
