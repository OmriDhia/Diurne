<?php

namespace App\Contremarque\Bus\Command\CarpetDesignOrderImages;

class UpdateImageTypeResponse
{
    public function __construct(private readonly string $status, private readonly int $id_image)
    {
    }


    public function toArray(): array
    {
        return ['status' => $this->status, 'id_image' => $this->id_image];
    }
}