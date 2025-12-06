<?php

declare(strict_types=1);

namespace App\User\Bus\Command\Profile;

use App\Common\Bus\Command\CommandResponse;

final class ProfileResponse implements CommandResponse
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
     * @psalm-return array{profile_id: int, name: string}
     */
    public function toArray(): array
    {
        return [
            'profile_id' => $this->id,
            'name' => $this->name,
        ];
    }
}
