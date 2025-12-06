<?php
declare(strict_types=1);

namespace App\Workshop\Repository;

use App\Common\Repository\BaseRepository;
use App\Workshop\Entity\WorkshopImage;


interface WorkshopImageRepository extends BaseRepository
{
    public function findOneByName(array $array): ?WorkshopImage;

}