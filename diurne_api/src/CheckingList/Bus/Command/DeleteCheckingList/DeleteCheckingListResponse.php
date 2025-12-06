<?php

namespace App\CheckingList\Bus\Command\DeleteCheckingList;

use App\Common\Bus\Command\CommandResponse;

class DeleteCheckingListResponse implements CommandResponse
{
    public function __construct(private readonly int $checkingListId)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->checkingListId,
        ];
    }
}
