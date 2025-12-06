<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\CustomerGroup;

use App\Common\Bus\Command\CommandResponse;

final class CustomerGroupResponse implements CommandResponse
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return (int|string)[]
     *
     * @psalm-return array{customerGroup_id: int, name: string}
     */
    public function toArray(): array
    {
        return [
            'customerGroup_id' => $this->id,
            'name' => $this->name,
        ];
    }
}
