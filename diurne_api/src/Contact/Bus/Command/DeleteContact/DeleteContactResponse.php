<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteContact;

use App\Common\Bus\Command\CommandResponse;

final class DeleteContactResponse implements CommandResponse
{
    public function __construct(
        public int $contactId,
    ) {
    }

    /**
     * @return int[]
     *
     * @psalm-return array{contactId: int}
     */
    public function toArray(): array
    {
        return [
            'contactId' => $this->contactId,
        ];
    }
}
