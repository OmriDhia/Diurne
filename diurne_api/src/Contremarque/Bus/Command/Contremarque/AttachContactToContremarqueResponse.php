<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\CommandResponse;

final readonly class AttachContactToContremarqueResponse implements CommandResponse
{
    public function __construct(
        private int $contremarqueId,
        private int $contactId,
        private bool $current
    ) {
    }

    /**
     * @return (bool|int)[]
     *
     * @psalm-return array{contremarqueId: int, contactId: int, current: bool}
     */
    public function toArray(): array
    {
        return [
            'contremarqueId' => $this->contremarqueId,
            'contactId' => $this->contactId,
            'current' => $this->current,
        ];
    }
}
