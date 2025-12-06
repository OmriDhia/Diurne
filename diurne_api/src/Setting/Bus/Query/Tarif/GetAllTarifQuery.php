<?php

declare(strict_types=1);

namespace App\Setting\Bus\Query\Tarif;

use App\Common\Bus\Query\Query;
use App\Setting\DTO\GetAllTarifRequestDto;

class GetAllTarifQuery implements Query
{
    public function __construct(private readonly GetAllTarifRequestDto $dto)
    {
    }

    public function getDto(): GetAllTarifRequestDto
    {
        return $this->dto;
    }
}
