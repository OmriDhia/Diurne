<?php

declare(strict_types=1);

namespace App\MobileAppApi\Repository;

use App\Common\Repository\BaseRepository;

interface UserMobileAppRepository extends BaseRepository
{
    public function search(?string $query): array;
}
