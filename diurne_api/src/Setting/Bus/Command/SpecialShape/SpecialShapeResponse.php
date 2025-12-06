<?php

declare(strict_types=1);

namespace App\Setting\Bus\Command\SpecialShape;

use App\Common\Bus\Command\CommandResponse;
use App\Setting\Entity\SpecialShape;

final readonly class SpecialShapeResponse implements CommandResponse
{
    public function __construct(
        private SpecialShape $specialShape
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->specialShape->getId(),
            'label' => $this->specialShape->getLabel(),
        ];
    }
}
