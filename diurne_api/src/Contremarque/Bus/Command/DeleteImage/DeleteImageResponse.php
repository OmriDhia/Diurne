<?php

namespace App\Contremarque\Bus\Command\DeleteImage;

use App\Common\Bus\Command\CommandResponse;

class DeleteImageResponse implements CommandResponse
{
    public function __construct(
        private readonly string $message
    )
    {
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}