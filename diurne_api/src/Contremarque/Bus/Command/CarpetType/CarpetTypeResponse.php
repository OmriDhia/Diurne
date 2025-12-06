<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\CarpetType;

use App\Common\Bus\Command\CommandResponse;

final class CarpetTypeResponse implements CommandResponse
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }

    /**
     * @return (int|string)[]
     *
     * @psalm-return array{carpetType_id: int, name: string}
     */
    public function toArray(): array
    {
        return [
            'carpetType_id' => $this->id,
            'name' => $this->name,
        ];
    }
}
