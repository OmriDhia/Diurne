<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetProjectDisByContremarque;

use App\Common\Bus\Query\QueryResponse;

final class GetProjectDisByContremarqueResponse implements QueryResponse
{
    public function __construct(public array $projectDis)
    {
    }

    public function toArray(): array
    {
        return ['projectDis' => $this->projectDis];
    }
}
