<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\DeleteAddress;

use App\Common\Bus\Command\CommandResponse;

final class DeleteAddressResponse implements CommandResponse
{
    public function __construct(
        public int $addressId,
    ) {
    }

    /**
     * @return int[]
     *
     * @psalm-return array{addressId: int}
     */
    public function toArray(): array
    {
        return [
            'addressId' => $this->addressId,
        ];
    }
}
