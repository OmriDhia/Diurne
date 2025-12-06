<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Contremarque;

use App\Common\Bus\Command\CommandResponse;

final readonly class DetachContactFromContremarqueResponse implements CommandResponse
{
    public function __construct(
        private int $contremarqueId,
        private int $contactId
    ) {
    }

    /**
     * @return int[]
     *
     * @psalm-return array{contremarqueId: int, contactId: int}
     */
    public function toArray(): array
    {
        return [
            'contremarqueId' => $this->contremarqueId,
            'contactId' => $this->contactId,
        ];
    }
}
