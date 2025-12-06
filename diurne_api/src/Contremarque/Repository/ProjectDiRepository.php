<?php

declare(strict_types=1);

namespace App\Contremarque\Repository;

use App\Common\Repository\BaseRepository;
use App\Contremarque\Entity\ProjectDi;

interface ProjectDiRepository extends BaseRepository
{
    public function findOneByDemandeNumber(string $demandeNumber): ?ProjectDi;
    public function generateProjectNumber(): string;
}
