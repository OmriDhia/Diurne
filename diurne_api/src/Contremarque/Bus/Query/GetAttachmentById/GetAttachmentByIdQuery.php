<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Query\GetAttachmentById;

use App\Common\Bus\Query\Query;

final readonly class GetAttachmentByIdQuery implements Query
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
