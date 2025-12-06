<?php
declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetRnAttributionById;

use App\Common\Bus\Query\Query;

class GetRnAttributionByIdQuery implements Query
{
    /**
     * @param int $id
     */
    public function __construct(
        public readonly int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

}