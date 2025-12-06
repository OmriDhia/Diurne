<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DeleteDesignerComposition;

use App\Common\Bus\Command\CommandResponse;

final readonly class DeleteDesignerCompositionResponse implements CommandResponse
{
    public function __construct(
        private string $message
    ) {
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
