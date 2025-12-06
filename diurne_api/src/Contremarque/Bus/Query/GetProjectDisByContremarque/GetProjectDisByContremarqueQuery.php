<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetProjectDisByContremarque;

use App\Common\Bus\Query\Query;

final readonly class GetProjectDisByContremarqueQuery implements Query
{
    public function __construct(private int $contremarqueId)
    {
    }

    public function getContremarqueId(): int
    {
        return $this->contremarqueId;
    }
}
