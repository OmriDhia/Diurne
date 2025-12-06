<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\DesignerComposition;

use App\Common\Bus\Command\CommandResponse;
use App\Contremarque\Entity\DesignerComposition;

class UpdateDesignerCompositionResponse implements CommandResponse
{
    public function __construct(private readonly DesignerComposition $designerComposition)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->designerComposition->getId(),
            'material' => $this->designerComposition->getMaterial() ? $this->designerComposition->getMaterial()->getId() : null,
            'rate' => $this->designerComposition->getRate(),
        ];
    }
}
