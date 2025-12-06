<?php

declare(strict_types=1);

namespace App\Setting\Repository;

use App\Common\Repository\BaseRepository;

interface MaterialRepository extends BaseRepository
{
    public function getMaterialTranslations(array $materialIds, string $isoCode = 'fr'): array;
    public function getMaterialComposition(array $data, string $isoCode = 'fr'): string;
}
